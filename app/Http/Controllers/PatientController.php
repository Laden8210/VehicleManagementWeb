<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Patient::all();
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
            'PatientName' => 'required|string|max:255',
            'Date' => 'required|string|max:255',
            'Age' => 'required|string|max:255',
            'PatientNumber' => 'required|string|max:255',
            'PatientAddress' => 'required|string|max:255',
            'PatientDiagnosis' => 'required|string|max:255',
            'dispatches_id' => 'required|exists:dispatches,id',  // Foreign key validation
            'trip_tickets_id' => 'required|exists:trip_tickets,id', // Foreign key validation
        ]);

        $patient = Patient::create($validatedData); // Store a new dispatch
        return response()->json($patient, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return response()->json($patient);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validatedData = $request->validate([
            'PatientName' => 'required|string|max:255',
            'Gender' => 'required|string|max:255',
            'Age' => 'required|string|max:255',
            'PatientNumber' => 'required|string|max:255',
            'PatientAddress' => 'required|string|max:255',
            'PatientDiagnosis' => 'required|string|max:255',
            'dispatches_id' => 'required|exists:dispatches,id',  // Foreign key validation
            'trip_tickets_id' => 'required|exists:trip_tickets,id', // Foreign key validation
        ]);

        $patient = Patient::create($validatedData); // Store a new dispatch
        return response()->json($patient, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete(); // Delete the patient
        return response()->json(null, 204); // Return a 204 No Content response
    }
}
