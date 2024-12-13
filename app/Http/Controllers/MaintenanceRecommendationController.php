<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRecommendation;
use App\Models\RepairAndMaintenance;
use Illuminate\Http\Request;

class MaintenanceRecommendationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return MaintenanceRecommendation::all();
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
            'MRNumber' => 'required|string|max:255',
            'personnels_id' => 'required|exists:personnels,id',  // Foreign key validation
            'vehicles_id' => 'required|exists:vehicles,id', // Foreign key validation
            'RecommendationType' => 'required|string|max:255',
            'Description' => 'required|string|max:255',
            'RecommendationDate' => 'required|date_format:Y-m-d',
            'DueDate' => 'required|date_format:Y-m-d',
            'PriorityLevel' => 'required|string|max:255',
        ]);

        $maintenanceRecommendation = MaintenanceRecommendation::create($validatedData); // Store a new dispatch
        return response()->json($maintenanceRecommendation, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MaintenanceRecommendation $maintenanceRecommendation)
    {
        return response()->json($maintenanceRecommendation);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaintenanceRecommendation $maintenanceRecommendation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MaintenanceRecommendation $maintenanceRecommendation)
    {
        $validatedData = $request->validate([
            'MRNumber' => 'required|string|max:255',
            'personnels_id' => 'required|exists:personnels,id',
            'vehicles_id' => 'required|exists:vehicles,id',
            'RecommendationType' => 'required|string|max:255',
            'Description' => 'required|string|max:255',
            'RecommendationDate' => 'required|date_format:Y-m-d',
            'DueDate' => 'required|date_format:Y-m-d',
            'PriorityLevel' => 'required|string|max:255',
            'RequestStatus' => 'nullable|string', // Allow null for this field
        ]);

        if (isset($validatedData['RequestStatus']) && $validatedData['RequestStatus'] === 'Approved') {
            RepairAndMaintenance::create([
                'repair_requests_id' => null,
                'maintenance_recommendations_id' => $maintenanceRecommendation->id,
            ]);
        }
        return response()->json($maintenanceRecommendation, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaintenanceRecommendation $maintenanceRecommendation)
    {
        $maintenanceRecommendation->delete(); // Delete the maintenance recommendation
        return response()->json(null, 204); // Return a 204 No Content response
    }

    public function print($id)
    {
        $maintenanceRecommendations = MaintenanceRecommendation::findOrFail($id);

        return view('maintenance_recommendations.print', compact('maintenanceRecommendations')); // Ensure you create this view
    }
}
