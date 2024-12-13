<?php

namespace App\Http\Controllers;

use App\Models\ServiceRecords;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceRecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ServiceRecords::all();
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
            'maintenance_recommendations_id' => 'required|exists:maintenance_recommendations,id',  // Foreign key validation
            'suppliers_id' => 'required|exists:suppliers,id', // Foreign key validation
            'MaintenanceDate' => 'required|date_format:Y-m-d',
            'MaintenanceType' => 'required|string|max:255',
            'ServiceDescription' => 'required|string|max:255',
            'ChangedParts' => 'required|string|max:255',
            'ServiceCosts' => 'required|numeric|regex:/^\d{1,8}(\.\d{2})?$/',            
        ]);

        $serviceRecords = ServiceRecords::create($validatedData); // Store a new dispatch
        return response()->json($serviceRecords, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceRecords $serviceRecords)
    {
        return response()->json($serviceRecords);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceRecords $serviceRecords)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceRecords $serviceRecords)
    {
        $validatedData = $request->validate([
            'maintenance_recommendations_id' => 'required|exists:maintenance_recommendations,id',  // Foreign key validation
            'suppliers_id' => 'required|exists:suppliers,id', // Foreign key validation
            'MaintenanceDate' => 'required|date_format:Y-m-d',
            'MaintenanceType' => 'required|string|max:255',
            'ServiceDescription' => 'required|string|max:255',
            'ChangedParts' => 'required|string|max:255',
            'ServiceCosts' => 'required|numeric|regex:/^\d{1,8}(\.\d{2})?$/',            
        ]);

        $serviceRecords = ServiceRecords::create($validatedData); // Store a new dispatch
        return response()->json($serviceRecords, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceRecords $serviceRecords)
    {
        $serviceRecords->delete(); // Delete the service records
        return response()->json(null, 204); // Return a 204 No Content response
    }
}
