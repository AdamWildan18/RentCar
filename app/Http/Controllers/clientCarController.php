<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Http\Request;

class clientCarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedDates = $request->input('dates'); // Format: "YYYY-MM-DD to YYYY-MM-DD"

        if ($selectedDates) {
            $dates = explode(' to ', $selectedDates);
            $start = Carbon::parse($dates[0]);
            $end = Carbon::parse($dates[1]);

            // Filter cars that are not reserved for the selected dates
            $cars = Car::whereDoesntHave('reservations', function ($query) use ($start, $end) {
                $query->where('status', 'Reserved')
                      ->where(function ($query) use ($start, $end) {
                          $query->whereBetween('start_date', [$start, $end])
                                ->orWhereBetween('end_date', [$start, $end])
                                ->orWhere(function ($query) use ($start, $end) {
                                    $query->where('start_date', '<=', $start)
                                          ->where('end_date', '>=', $end);
                                });
                      });
            })->paginate(9);
        } else {
            // If no dates selected, show all available cars
            $cars = Car::where('status', '=', 'available')->paginate(9);
        }

        return view('cars.cars', compact('cars', 'selectedDates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
