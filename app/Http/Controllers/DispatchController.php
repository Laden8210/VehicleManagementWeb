<?php

namespace App\Http\Controllers;

use App\Models\Dispatch;
use Illuminate\Http\Request;

class DispatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Dispatch::all(); // Return all dispatches
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
            'RequestDate' => 'required|date_format:Y-m-d',
            'RequestorName' => 'required|string|max:255',
            'TravelDate' => 'required|date_format:Y-m-d',
            'PickupTime' => 'required|date_format:H:i',
            'Destination' => 'required|string|max:255',
            'RequestStatus' => 'required|string|max:255',
            'Remarks' => 'required|string|max:255',
        ]);

        $dispatch = Dispatch::create($validatedData); // Store a new dispatch
        return response()->json($dispatch, 201); // Return the newly created dispatch with a 201 status
    }

    /**
     * Display the specified resource.
     */
    public function show(Dispatch $dispatch)
    {
        return response()->json($dispatch);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dispatch $dispatch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dispatch $dispatch)
    {
        $validatedData = $request->validate([
            'RequestDate' => 'required|date_format:Y-m-d',
            'RequestorName' => 'required|string|max:255',
            'TravelDate' => 'required|date_format:Y-m-d',
            'PickupTime' => 'required|date_format:H:i',
            'Destination' => 'required|string|max:255',
            'RequestStatus' => 'required|string|max:255',
            'Remarks' => 'required|string|max:255',
        ]);

        $dispatch = Dispatch::create($validatedData); // Store a new dispatch
        return response()->json($dispatch, 201); // Return the newly created dispatch with a 201 status
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dispatch $dispatch)
    {
        $dispatch->delete(); // Delete the dispatch
        return response()->json(null, 204); // Return a 204 No Content response
    }


}
