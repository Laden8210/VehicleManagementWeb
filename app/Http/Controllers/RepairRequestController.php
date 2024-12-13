<?php

namespace App\Http\Controllers;

use App\Models\RepairRequest;
use App\Models\RepairAndMaintenance;
use Illuminate\Http\Request;

class RepairRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return RepairRequest::all();
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
            'RRNumber' => 'required|string|max:255',
            'personnels_id' => 'required|exists:personnels,id',
            'vehicles_id' => 'required|exists:vehicles,id',
            'RequestDate' => 'required|date_format:Y-m-d',
            'ReportedIssue' => 'required|string|max:255',
            'IssueDescription' => 'required|string|max:255',
            'Issues.*.IssueDescription' => 'required|string',
            'PriorityLevel' => 'required|string|max:255',
            'RequestStatus' => 'required|string|max:255',
        ]);

        $repairRequest = RepairRequest::create($validatedData);
        return response()->json($repairRequest, 201);

        RepairRequest::create($validatedData);
        return redirect()->route('repair_requests.print');

    }

    /**
     * Display the specified resource.
     */
    public function show(RepairRequest $repairRequest)
    {
        return response()->json($repairRequest);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RepairRequest $repairRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RepairRequest $repairRequest)
    {
        $validatedData = $request->validate([
            'RRNumber' => 'required|string|max:255',
            'personnels_id' => 'required|exists:personnels,id',  // Foreign key validation
            'vehicles_id' => 'required|exists:vehicles,id', // Foreign key validation
            'RequestDate' => 'required|date_format:Y-m-d',
            'ReportedIssue' => 'required|string|max:255',
            'IssueDescription' => 'required|string|max:255',
            'Issues.*.IssueDescription' => 'required|string',
            'PriorityLevel' => 'required|string|max:255',
            'RequestStatus' => 'required|string|max:255',
        ]);

        $repairRequest->update($validatedData);

        if ($validatedData['RequestStatus'] === 'Approved') {
            RepairAndMaintenance::create([
                'repair_requests_id' => $repairRequest->id,
                'maintenance_recommendations_id' => null,
            ]);
        }
        return response()->json($repairRequest, 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RepairRequest $repairRequest)
    {
        $repairRequest->delete(); // Delete the repair request
        return response()->json(null, 204); // Return a 204 No Content response
    }

    public function print($id)
    {
        $repairRequest = RepairRequest::findOrFail($id);

        return view('repair_requests.print', compact('repairRequest')); // Ensure you create this view
    }
}
