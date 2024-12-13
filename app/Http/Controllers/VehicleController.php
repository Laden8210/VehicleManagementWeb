<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Vehicle::all(); // Return all vehicles
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
                    
        $image = $this->saveImage($request->$image, 'vehicles');
        
        $validatedData = $request->validate([
            'VehicleName' => 'required|string|max:255',
            'MvfileNo' => 'string|max:255',
            'PlateNumber' => 'string|max:255',
            'EngineNumber' => 'required|string|max:255',
            'ChassisNumber' => 'required|string|max:255',
            'Fuel' => 'required|string|max:255',
            'Make' => 'required|string|max:255',
            'Series' => 'required|string|max:255',
            'BodyType' => 'required|string|max:255',
            'YearModel' => 'required|integer|digits:4|min:1900|max:' . date('Y'),
            'Color' => 'required|string|max:255',
            'PurchasedDate' => 'required|string|max:255',
            'RegistrationDate' => 'required|string|max:255',
            'OrcrNo' => 'required|string|max:255',
            'PurchasedCost' => 'required|string|max:255',
            'PropertyNumber' => 'required|string|max:255',
            'Image' => $image,
            'OrcrImage' => $image,

        ]);

        $vehicle = Vehicle::create($validatedData); // Store a new vehicle
        return response()->json($vehicle, 201); // Return the newly created vehicle with a 201 status
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        return response()->json($vehicle); // Return the found vehicle
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validatedData = $request->validate([
            'VehicleName' => 'required|string|max:255',
            'MvfileNo' => 'string|max:255',
            'PlateNumber' => 'string|max:255',
            'EngineNumber' => 'required|string|max:255',
            'ChassisNumber' => 'required|string|max:255',
            'Fuel' => 'required|string|max:255',
            'Make' => 'required|string|max:255',
            'Series' => 'required|string|max:255',
            'BodyType' => 'required|string|max:255',
            'YearModel' => 'required|integer|digits:4|min:1900|max:' . date('Y'),
            'Color' => 'required|string|max:255',
            'PurchasedDate' => 'required|string|max:255',
            'RegistrationDate' => 'required|string|max:255',
            'OrcrNo' => 'required|string|max:255',
            'PurchasedCost' => 'required|string|max:255',
            'PropertyNumber' => 'required|string|max:255',
            'Image' => 'required|string|max:255',
            'OrcrImage' => 'required|string|max:255',
            // Add any other necessary validation rules
        ]);

        $vehicle->update($validatedData); // Update the vehicle
        return response()->json($vehicle, 200); // Return the updated vehicle
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete(); // Delete the vehicle
        return response()->json(null, 204); // Return a 204 No Content response
    }
}
