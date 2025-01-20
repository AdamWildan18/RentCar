<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = false; // set to true when in production
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function charge(Request $request)
{
    try {
        // Data untuk charge
        $request->validate([
            'gross_amount' => 'required|numeric|min:1',
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        $transactionDetails = [
            'order_id' => uniqid(),
            'gross_amount' => $request->gross_amount, // total harga
        ];

        $itemDetails = [
            [
                'id' => 'item01',
                'price' => $request->gross_amount,
                'quantity' => 1,
                'name' => 'Rental Car'
            ]
        ];

        $transaction = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $request->name,
                'email' => $request->email
            ]
        ];

        $snapToken = Snap::getSnapToken($transaction);

        return response()->json(['snap_token' => $snapToken]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}