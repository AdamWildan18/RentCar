<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($car_id)
    {
        $user = auth()->user();
        $car = Car::find($car_id);

        $reservedDates = Reservation::where('car_id', $car_id)
            ->where('status', 'Reserved')
            ->get(['start_date', 'end_date'])
            ->flatMap(function ($reservation) {
                // Ambil rentang tanggal dari start_date hingga end_date untuk setiap reservasi
                return Carbon::parse($reservation->start_date)->toPeriod(
                    Carbon::parse($reservation->end_date), '1 day'
                )->toArray();
            })
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d'); // Format tanggal ke Y-m-d
            })
            ->toArray();

        return view('reservation.create', compact('car', 'user', 'reservedDates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $car_id)
    {
        $request->validate([
            'full-name' => 'required|string|max:255',
            'email' => 'required|email',
            'reservation_dates' => 'required',
            'ktp' => 'required',
        ]);

        $car = Car::find($car_id);
        $user = Auth::user();

        // Extract start and end dates
        $reservation_dates = explode(' to ', $request->reservation_dates);
        $start = Carbon::parse($reservation_dates[0]);
        $end = Carbon::parse($reservation_dates[1]);

        // Check car availability (only check 'reserved' status)
        $isReserved = Reservation::where('car_id', $car_id)
            ->where('status', 'Reserved')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                      ->orWhereBetween('end_date', [$start, $end])
                      ->orWhere(function ($query) use ($start, $end) {
                          $query->where('start_date', '<=', $start)
                                ->where('end_date', '>=', $end);
                      });
            })
            ->exists();

        if ($isReserved) {
            return redirect()->back()->with('error', 'The car is not available for the selected dates.');
        }

        // Check if the user has more than 2 reservations
        $userReservationsCount = Reservation::where('user_id', $user->id)->count();
        if ($userReservationsCount >= 2) {
            return redirect()->back()->with('error', 'You cannot have more than 2 active reservations 😉.');
        }

        // Create a new reservation
        $reservation = new Reservation();
        $reservation->user()->associate($user);
        $reservation->car()->associate($car);
        $reservation->start_date = $start;
        $reservation->end_date = $end;
        $reservation->days = $start->diffInDays($end) + 1; // Include end date
        $reservation->price_per_day = $car->price_per_day;
        $reservation->total_price = $reservation->days * $reservation->price_per_day;
        $reservation->status = 'Reserved';
        $reservation->payment_method = 'At store';
        $reservation->payment_status = 'Pending';
        $reservation->save();

        return view('thankyou', ['reservation' => $reservation]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    // Edit and Update Payment status
    public function editPayment(Reservation $reservation)
    {
        $reservation = Reservation::find($reservation->id);
        return view('admin.updatePayment', compact('reservation'));
    }

    public function updatePayment(Reservation $reservation, Request $request)
    {
        $reservation = Reservation::find($reservation->id);
        $reservation->payment_status = $request->payment_status;
        $reservation->save();
        return redirect()->route('adminDashboard');
    }

    // Edit and Update Reservation Status
    public function editStatus(Reservation $reservation)
    {
        $reservation = Reservation::find($reservation->id);
        return view('admin.updateStatus', compact('reservation'));
    }

    public function updateStatus(Reservation $reservation, Request $request)
    {
        $reservation = Reservation::find($reservation->id);
        $reservation->status = $request->status;
        $car = $reservation->car;
        if($request->status == 'Ended' || $request->status == 'Canceled' ){
            $car->status = 'Available';
            $car->save();
        }
        $reservation->save();
        return redirect()->route('adminDashboard');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }

    public function payment(Request $request, $reservation_id)
    {
        $reservation = Reservation::findOrFail($reservation_id);
        $user = $reservation->user;

        // Konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_ENV') === 'production';
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Detail pembayaran
        $transaction_details = [
            'order_id' => 'ORDER-' . $reservation->id,
            'gross_amount' => $reservation->total_price, // Sesuaikan jumlah DP yang dibayar
        ];

        // Item detail (bisa berisi informasi lebih lanjut tentang produk)
        $items = [
            [
                'id' => 'car-' . $reservation->car_id,
                'price' => $reservation->total_price,
                'quantity' => 1,
                'name' => $reservation->car->brand . ' ' . $reservation->car->model,
            ],
        ];

        // Data pelanggan
        $customer_details = [
            'first_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone, // Sesuaikan jika ada field phone di User
        ];

        // Membuat transaksi Midtrans
        $midtrans_transaction = [
            'payment_type' => 'credit_card', // Atau sesuaikan dengan metode pembayaran yang diinginkan
            'transaction_details' => $transaction_details,
            'item_details' => $items,
            'customer_details' => $customer_details,
        ];

        try {
            // Mendapatkan URL pembayaran dari Midtrans
            $snapToken = Snap::getSnapToken($midtrans_transaction);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Pembayaran gagal: ' . $e->getMessage());
        }

        return view('payment.midtrans', compact('snapToken'));
    }

    public function paymentCallback(Request $request)
    {
        $status = $request->input('status_code');
        $order_id = $request->input('order_id');
        $reservation = Reservation::where('id', substr($order_id, 6))->first();

        if ($status == '200') {
            // Pembayaran berhasil
            $reservation->payment_status = 'Paid';
            $reservation->save();
            return redirect()->route('reservation.success');
        } else {
            // Pembayaran gagal
            $reservation->payment_status = 'Failed';
            $reservation->save();
            return redirect()->route('reservation.failed');
        }
    }
}
