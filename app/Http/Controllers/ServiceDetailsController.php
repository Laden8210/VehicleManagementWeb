<?php

namespace App\Http\Controllers;

use App\Models\ServiceDetails;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ServiceDetails::all();
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
            'repair_requests_id' => 'required|exists:repair_requests,id',  // Foreign key validation
            'suppliers_id' => 'required|exists:suppliers,id', // Foreign key validation
            'RepairDate' => 'required|date_format:Y-m-d',
            'RepairType' => 'required|string|max:255',
            'ServiceDescription' => 'required|string|max:255',
            'ChangedParts' => 'required|string|max:255',
            'ServiceCosts' => 'required|numeric|regex:/^\d{1,8}(\.\d{2})?$/',            
        ]);

        $serviceDetails = ServiceDetails::create($validatedData); // Store a new dispatch
        return response()->json($serviceDetails, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceDetails $serviceDetails)
    {
        return response()->json($serviceDetails);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceDetails $serviceDetails)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceDetails $serviceDetails)
    {
        $validatedData = $request->validate([
            'repair_requests_id' => 'required|exists:repair_requests,id',  // Foreign key validation
            'suppliers_id' => 'required|exists:suppliers,id', // Foreign key validation
            'RepairDate' => 'required|date_format:Y-m-d',
            'RepairType' => 'required|string|max:255',
            'ServiceDescription' => 'required|string|max:255',
            'ChangedParts' => 'required|string|max:255',
            'ServiceCosts' => 'required|numeric|regex:/^\d{1,8}(\.\d{2})?$/',            
        ]);

        $serviceDetails = ServiceDetails::create($validatedData); // Store a new dispatch
        return response()->json($serviceDetails, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceDetails $serviceDetails)
    {
        $serviceDetails->delete(); // Delete the service details
        return response()->json(null, 204); // Return a 204 No Content response
    }
}
