<?php

namespace App\Http\Controllers;

use App\Models\TripTicket;
use App\Models\Vehicle;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class TripTicketController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TripTicket::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicles = Vehicle::all(); // Make sure to import the Vehicle model
        $personnels = Personnel::all(); // Make sure to import the Personnel model
        return view('tripTickets.create', compact('vehicles', 'personnels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log all request data for debugging
        Log::info('Incoming Request Data:', $request->all());

        try {
            // Validate the request data
            $validatedData = $request->validate([
                'TripTicketNumber' => 'required|string|max:255',
                'ArrivalDate' => 'required|date_format:Y-m-d',
                'ReturnDate' => 'required|date_format:Y-m-d',
                'personnels_id' => 'required|exists:personnels,id',
                'vehicles_id' => 'required|exists:vehicles,id',
                'Origin' => 'required|string|max:255',
                'Destination' => 'required|string|max:255',
                'Purpose' => 'required|string|max:255',
                'KmBeforeTravel' => 'required|integer',
                'KmAfterTravel' => 'nullable|integer',
                'DistanceTravelled' => 'nullable|integer',
                'TimeDeparture_A' => 'required|date_format:H:i',
                'TimeArrival_A' => 'nullable|date_format:H:i',
                'TimeDeparture_B' => 'nullable|date_format:H:i',
                'TimeArrival_B' => 'nullable|date_format:H:i',
                'BalanceStart' => 'nullable|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
                'IssuedFromOffice' => 'nullable|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
                'AddedDuringTrip' => 'nullable|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
                'TotalFuelTank' => 'nullable|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
                'FuelConsumption' => 'nullable|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
                'BalanceEnd' => 'nullable|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
                'responders' => 'required|array|min:1',
                'responders.*.responder_id' => 'required|exists:personnels,id',
                'Others' => 'nullable|string|max:255',
                'Remarks' => 'nullable|string|max:255',
            ]);

            // Log validated data
            Log::info('Validated Data:', $validatedData);

            // Convert responders to JSON
            $validatedData['responders'] = json_encode($validatedData['responders']);

            // Save to database
            $tripTicket = TripTicket::create($validatedData);

            // Return success response
            return response()->json($tripTicket, 201);
        } catch (\Throwable $e) {
            // Log validation or other errors
            Log::error('Error Occurred:', ['message' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(TripTicket $tripTicket)
    {
        return response()->json($tripTicket);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TripTicket $tripTicket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TripTicket $tripTicket)
    {
        Log::info('Form Submission Data:', $request->all());

        // Validate the request
        $validatedData = $request->validate([
            'TripTicketNumber' => 'required|string|max:255',
            'ArrivalDate' => 'required|date_format:Y-m-d',
            'ReturnDate' => 'required|date_format:Y-m-d',
            'personnels_id' => 'required|exists:personnels,id', // Foreign key validation
            'vehicles_id' => 'required|exists:vehicles,id', // Foreign key validation
            'Origin' => 'required|string|max:255',
            'Destination' => 'required|string|max:255',
            'Purpose' => 'required|string|max:255',
            'KmBeforeTravel' => 'required|integer',
            'KmAfterTravel' => 'nullable|integer',
            'DistanceTravelled' => 'nullable|integer',
            'TimeDeparture_A' => 'required|date_format:H:i',
            'TimeArrival_A' => 'nullable|date_format:H:i',
            'TimeDeparture_B' => 'nullable|date_format:H:i',
            'TimeArrival_B' => 'nullable|date_format:H:i',
            'BalanceStart' => 'nullable|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
            'IssuedFromOffice' => 'nullable|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
            'AddedDuringTrip' => 'nullable|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
            'TotalFuelTank' => 'nullable|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
            'FuelConsumption' => 'nullable|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
            'BalanceEnd' => 'nullable|numeric|regex:/^\d{1,8}(\.\d{2})?$/',
            'responders' => 'required|array|min:1',
            'responders.*.responder_id' => 'required|exists:personnels,id',
            'Others' => 'nullable|string|max:255',
            'Remarks' => 'nullable|string|max:255',
        ]);

        // Convert responders to JSON
        $validatedData['responders'] = json_encode($validatedData['responders']);

        // Create the trip ticket
        $tripTicket = TripTicket::create($validatedData);

        return response()->json($tripTicket, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TripTicket $tripTicket)
    {
        $tripTicket->delete(); // Delete the trip ticket
        return response()->json(null, 204); // Return a 204 No Content response
    }

    public function print($id)
    {
        $tripTicket = TripTicket::findOrFail($id);

        return view('trip_tickets.print', compact('tripTicket')); // Ensure you create this view
    }


}
