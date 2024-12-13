<?php

namespace App\Http\Controllers;

use App\Models\FuelConsumption;
use Illuminate\Http\Request;

class FuelConsumptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return FuelConsumption::all();
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
        $validatedData = $request->validate([
            'WithdrawalSlipNo' => 'required|string|max:255',
            'RequestDate' => 'required|date_format:Y-m-d',
            'ReferenceNumber' => 'required|string|max:255',
            'trip_tickets_id' => 'required|exists:trip_tickets,id',  // Foreign key validation
            'Quantity' => 'required|integer',
            'Price' => 'required|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
            'Amount' => 'required|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
            'PreviousBalance' => 'required|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
            'RemainingBalance' => 'required|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
        ]);

        $fuelConsumption = FuelConsumption::create($validatedData); // Store a new dispatch
        return response()->json($fuelConsumption, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(FuelConsumption $fuelConsumption)
    {
        return response()->json($fuelConsumption);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FuelConsumption $fuelConsumption)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FuelConsumption $fuelConsumption)
    {
        $validatedData = $request->validate([
            'WithdrawalSlipNo' => 'required|string|max:255',
            'RequestDate' => 'required|date_format:Y-m-d',
            'ReferenceNumber' => 'required|string|max:255',
            'trip_tickets_id' => 'required|exists:trip_tickets,id',  // Foreign key validation
            'Quantity' => 'required|integer',
            'Price' => 'required|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
            'Amount' => 'required|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
            'PreviousBalance' => 'required|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
            'RemainingBalance' => 'required|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
        ]);

        $fuelConsumption = FuelConsumption::create($validatedData); // Store a new dispatch
        return response()->json($fuelConsumption, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FuelConsumption $fuelConsumption)
    {
        $fuelConsumption->delete(); // Delete the fuel consumption
        return response()->json(null, 204); // Return a 204 No Content response
    }



    public function print($year, $month)
    {
        // Fetch data based on year and month
        $fuelConsumptions = FuelConsumption::whereYear('RequestDate', $year)
            ->whereMonth('RequestDate', $month)
            ->get();

        // Check if data is retrieved
        if ($fuelConsumptions->isEmpty()) {
            return redirect()->back()->with('error', 'No fuel consumption records found for the selected month and year.');
        }

        return view('fuel_consumptions.print', compact('fuelConsumptions', 'year', 'month'));
    }

    public function printFuelSlip($id)
    {
        $fuelSlips = FuelConsumption::findOrFail($id);

        return view('fuel_slips.print', compact('fuelSlips')); // Ensure you create this view
    }

}
