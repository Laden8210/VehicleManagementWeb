<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dispatch;
use App\Models\MaintenanceHistory;
use App\Models\MaintenanceRecommendation;
use App\Models\TripTicket;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\RepairRequest;
use App\Models\Reminder;
use App\Models\User;


class APIController extends Controller
{
    public function getDispatch()
    {
        return response()->json(Dispatch::all());
    }



    // Ticker Area

    public function getTicket()
    {

        $guest = Auth::guard('api')->user();
        return response()->json(
            TripTicket::where('user_id', $guest->id)->with(['vehicle','user'])
                ->get()
                ->map(function ($ticket) {
                    return [
                        'id' => $ticket->id,
                        'TripTicketNumber' => $ticket->TripTicketNumber,
                        'ArrivalDate' => $ticket->ArrivalDate,
                        'ReturnDate' => $ticket->ReturnDate,
                        'vehicles_id' => $ticket->vehicle->id,
                        'Origin' => $ticket->Origin,
                        'Destination' => $ticket->Destination,
                        'Purpose' => $ticket->Purpose,
                        'KmBeforeTravel' => $ticket->KmBeforeTravel,
                        'KmAfterTravel' => $ticket->KmAfterTravel,
                        'DistanceTravelled' => $ticket->DistanceTravelled,
                        'TimeDeparture_A' => $ticket->TimeDeparture_A,
                        'TimeArrival_A' => $ticket->TimeArrival_A,
                        'TimeDeparture_B' => $ticket->TimeDeparture_B,
                        'TimeArrival_B' => $ticket->TimeArrival_B,
                        'BalanceStart' => $ticket->BalanceStart,
                        'IssuedFromOffice' => $ticket->IssuedFromOffice,
                        'AddedDuringTrip' => $ticket->AddedDuringTrip,
                        'TotalFuelTank' => $ticket->TotalFuelTank,
                        'FuelConsumption' => $ticket->FuelConsumption,
                        'BalanceEnd' => $ticket->BalanceEnd,
                        'Others' => $ticket->Others,
                        'Remarks' => $ticket->Remarks,
                        'created_at' => $ticket->created_at,
                        'updated_at' => $ticket->updated_at,
                        'responder_personnels_ids' => $ticket->responder_personnels_ids,
                        'responders' => $ticket->responders,
                        'user_id' => $ticket->user_id,

                        'VehicleName' => $ticket->vehicle->VehicleName,
                        'MvfileNo' => $ticket->vehicle->MvfileNo,
                        'PlateNumber' => $ticket->vehicle->PlateNumber,
                        'EngineNumber' => $ticket->vehicle->EngineNumber,
                        'ChassisNumber' => $ticket->vehicle->ChassisNumber,
                        'Fuel' => $ticket->vehicle->Fuel,
                        'Make' => $ticket->vehicle->Make,
                        'Series' => $ticket->vehicle->Series,
                        'BodyType' => $ticket->vehicle->BodyType,
                        'YearModel' => $ticket->vehicle->YearModel,
                        'Color' => $ticket->vehicle->Color,
                        'PurchasedDate' => $ticket->vehicle->PurchasedDate,
                        'RegistrationDate' => $ticket->vehicle->RegistrationDate,
                        'OrcrNo' => $ticket->vehicle->OrcrNo,
                        'PurchasedCost' => $ticket->vehicle->PurchasedCost,
                        'PropertyNumber' => $ticket->vehicle->PropertyNumber,
                        'created_at' => $ticket->vehicle->created_at,
                        'updated_at' => $ticket->vehicle->updated_at,
                        'name' => $ticket->user->name,


                    ];
                })
        );
    }


    public function addTicket(Request $request)
    {
        $request->validate([
            'ArrivalDate' => 'required|date',
            'ReturnDate' => 'required|date',
            'vehicles_id' => 'required|exists:vehicles,id',
            'Origin' => 'required',
            'Destination' => 'required',
            'Purpose' => 'required',
            'KmBeforeTravel' => 'required|numeric',
            'BalanceStart' => 'required|numeric',
            'IssuedFromOffice' => 'required',
            'TimeDeparture_A' => 'required',
            'KmAfterTravel' => 'required|numeric',
            'TimeArrival_A' => 'required',
            'TimeDeparture_B' => 'required',
            'TimeArrival_B' => 'required',
            'AddedDuringTrip' => 'nullable',
            'Others' => 'nullable',
            'Remarks' => 'nullable',
        ]);

        $guest = Auth::guard('api')->user();

        $tripTicket =  new TripTicket();
        $tripTicket->user_id = $guest->id;
        $tripTicket->ArrivalDate = $request->ArrivalDate;
        $tripTicket->ReturnDate = $request->ReturnDate;
        $tripTicket->vehicles_id = $request->vehicles_id;
        $tripTicket->Origin = $request->Origin;
        $tripTicket->Destination = $request->Destination;
        $tripTicket->Purpose = $request->Purpose;
        $tripTicket->KmBeforeTravel = $request->KmBeforeTravel;
        $tripTicket->BalanceStart = $request->BalanceStart;
        $tripTicket->IssuedFromOffice = $request->IssuedFromOffice;

        $tripTicket->TimeDeparture_A = Carbon::createFromFormat('h:i A', $request->TimeDeparture_A)->format('H:i:s');
        $tripTicket->KmAfterTravel = $request->KmAfterTravel;
        $tripTicket->TimeArrival_A = Carbon::createFromFormat('h:i A', $request->TimeArrival_A)->format('H:i:s');
        $tripTicket->TimeDeparture_B = Carbon::createFromFormat('h:i A', $request->TimeDeparture_B)->format('H:i:s');
        $tripTicket->TimeArrival_B = Carbon::createFromFormat('h:i A', $request->TimeArrival_B)->format('H:i:s');

        $tripTicket->AddedDuringTrip = $request->AddedDuringTrip;

        $tripTicket->Others = $request->Others;
        $tripTicket->Remarks = $request->Remarks;
        $tripTicket->save();

        return response()->json(['message' => 'Trip ticket added successfully'], 201);
    }


    public function getVehicles()
    {
        return response()->json(Vehicle::all());
    }

    public function addRepairRequest(Request $request)
    {
        try {

            if (empty($request->vehicleName) || !is_numeric($request->vehicleName)) {
                return response()->json([
                    'message' => 'An error occurred while adding the repair request.',
                    'error' => 'The vehicle name is required and must be an integer.',
                ], 422);
            }

            if (empty($request->reportedIssue) || !is_string($request->reportedIssue)) {
                return response()->json([
                    'message' => 'An error occurred while adding the repair request.',
                    'error' => 'The reported issue field is required and must be a string.',
                ], 422);
            }

            if (empty($request->issueDescription) || !is_string($request->issueDescription)) {
                return response()->json([
                    'message' => 'An error occurred while adding the repair request.',
                    'error' => 'The issue description field is required and must be a string.',
                ], 422);
            }

            if (empty($request->priorityLevel) || !is_string($request->priorityLevel)) {
                return response()->json([
                    'message' => 'An error occurred while adding the repair request.',
                    'error' => 'The priority level is required and must be a string.',
                ], 422);
            }


            $dateFormat = 'd/m/Y';
            $requestDate = Carbon::createFromFormat($dateFormat, $request->requestDate);
            if ($requestDate->format($dateFormat) !== $request->requestDate) {
                return response()->json([
                    'message' => 'An error occurred while adding the repair request.',
                    'error' => 'The request date must be in the format d/m/Y.',
                ], 422);
            }


            $requestDate = $requestDate->format('Y-m-d');

            $repair = new RepairRequest();

            $repair->vehicles_id = $request->vehicleName;
            $repair->RequestDate = $requestDate;
            $repair->ReportedIssue = $request->reportedIssue;
            $repair->Issues = $request->issueDescription;
            $repair->PriorityLevel = $request->priorityLevel;


            $guest = Auth::guard('api')->user();
            $repair->user_id = $guest->id;
            $repair->RequestStatus = 'Pending';
            $repair->DisapprovalComments = '';

            $repair->save();


            return response()->json([
                'message' => 'Repair request added successfully!',
                'repairRequest' => $repair
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'An error occurred while adding the repair request.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function getRepairRequest()
    {
        $guest = Auth::guard('api')->user();
        return response()->json(
            RepairRequest::where('user_id', $guest->id)->with('vehicle')
                ->get()
                ->map(function ($ticket) {
                    return [
                        'id' => $ticket->id,
                        'RequestDate' => $ticket->RequestDate,
                        'ReportedIssue' => $ticket->ReportedIssue,
                        'Issues' => $ticket->Issues,
                        'PriorityLevel' => $ticket->PriorityLevel,
                        'RequestStatus' => $ticket->RequestStatus,
                        'DisapprovalComments' => $ticket->DisapprovalComments,
                        'RRNumber' => $ticket->RRNumber,
                        'created_at' => $ticket->created_at,
                        'updated_at' => $ticket->updated_at,
                        'user_id' => $ticket->user_id,

                        // Vehicle-related fields
                        'VehicleName' => $ticket->vehicle->VehicleName,
                        'MvfileNo' => $ticket->vehicle->MvfileNo,
                        'PlateNumber' => $ticket->vehicle->PlateNumber,
                        'EngineNumber' => $ticket->vehicle->EngineNumber,
                        'ChassisNumber' => $ticket->vehicle->ChassisNumber,
                        'Fuel' => $ticket->vehicle->Fuel,
                        'Make' => $ticket->vehicle->Make,
                        'Series' => $ticket->vehicle->Series,
                        'BodyType' => $ticket->vehicle->BodyType,
                        'YearModel' => $ticket->vehicle->YearModel,
                        'Color' => $ticket->vehicle->Color,
                        'PurchasedDate' => $ticket->vehicle->PurchasedDate,
                        'RegistrationDate' => $ticket->vehicle->RegistrationDate,
                        'OrcrNo' => $ticket->vehicle->OrcrNo,
                        'PurchasedCost' => $ticket->vehicle->PurchasedCost,
                        'PropertyNumber' => $ticket->vehicle->PropertyNumber,

                    ];
                })
        );
    }

    public function getReminder()
    {

        $guest = Auth::guard('api')->user();

        $reminder = Reminder::where('user_id', $guest->id)->get();

        return response()->json($reminder);
    }
    public function getMaintenanceRecommendations()
    {

        $guest = Auth::guard('api')->user();


        $maintenance = MaintenanceRecommendation::where('user_id', $guest->id)->with('vehicle')
            ->get();

            $formattedMaintenance = $maintenance->map(function ($item) {

                return [
                    'id' => $item->id,
                    'MRNumber' => $item->MRNumber,
                    'vehicles_id' => $item->vehicles_id,
                    'RecommendationType' => $item->RecommendationType,
                    'Issues' => $item->Issues,
                    'RecommendationDate' => $item->RecommendationDate,
                    'DueDate' => $item->DueDate,
                    'PriorityLevel' => $item->PriorityLevel,
                    'RequestStatus' => $item->RequestStatus,
                    'DisapprovalComments' => $item->DisapprovalComments,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                    'user_id' => $item->user_id,

                    // Vehicle-related fields
                    'vehicle_id' => $item->vehicle->id,
                    'VehicleName' => $item->vehicle->VehicleName,
                    'MvfileNo' => $item->vehicle->MvfileNo,
                    'PlateNumber' => $item->vehicle->PlateNumber,
                    'EngineNumber' => $item->vehicle->EngineNumber,
                    'ChassisNumber' => $item->vehicle->ChassisNumber,
                    'Fuel' => $item->vehicle->Fuel,
                    'Make' => $item->vehicle->Make,
                    'Series' => $item->vehicle->Series,
                    'BodyType' => $item->vehicle->BodyType,
                    'YearModel' => $item->vehicle->YearModel,
                    'Color' => $item->vehicle->Color,
                    'PurchasedDate' => $item->vehicle->PurchasedDate,
                    'RegistrationDate' => $item->vehicle->RegistrationDate,
                    'OrcrNo' => $item->vehicle->OrcrNo,
                    'PurchasedCost' => $item->vehicle->PurchasedCost,
                    'PropertyNumber' => $item->vehicle->PropertyNumber,
                    'created_at' => $item->vehicle->created_at,
                    'updated_at' => $item->vehicle->updated_at,
                ];
            });


        return response()->json($formattedMaintenance);
    }

    public function getDriver()
    {
        return response()->json(User::all());
    }

    public function addMaintenanceRecommendations(Request $request)
    {

        $validatedData = $request->validate([
            'driverID' => 'required|integer',
            'dueDate' => 'required|date',
            'issueDescription' => 'required|string|max:255',
            'issues' => 'required|string|max:255',
            'priorityLevel' => 'required',
            'recommendationDate' => 'required|date',
            'recommendationType' => 'required|string|max:255',
            'vehicleName' => 'required|integer|exists:vehicles,id',
        ]);

        try {
            $recommendation = new MaintenanceRecommendation();
            $recommendation->user_id = $validatedData['driverID'];
            $recommendation->PriorityLevel =$validatedData['priorityLevel'];
            $recommendation->DueDate = Carbon::parse($validatedData['dueDate'])->format('Y-m-d H:i:s');

            $recommendation->PriorityLevel = $validatedData['priorityLevel'];
            $recommendation->RecommendationType = $validatedData['recommendationType'];
            $recommendation->RecommendationDate = Carbon::parse($validatedData['recommendationDate'])->format('Y-m-d H:i:s');
            $recommendation->vehicles_id = $validatedData['vehicleName'];
            $recommendation->RequestStatus = "Pending";
            $recommendation->Issues = '[{"IssueDescription": "' . $validatedData['issueDescription'] . '"}]';



            $recommendation->save();

            return response()->json(['message' => 'Maintenance recommendation added successfully!'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the recommendation.'.$e], 500);
        }
    }

    public function getData()
    {
        try {
            // Retrieve the authenticated user
            $guest = Auth::guard('api')->user();

            // Check if the user is authenticated
            if (!$guest) {
                return response()->json([
                    'error' => 'Unauthorized access. User not authenticated.',
                ], 401);
            }

            $reminderCount = Reminder::where('user_id', $guest->id)->count();
            $repairRequestCount = RepairRequest::where('user_id', $guest->id)->count();
            $mainCount = MaintenanceRecommendation::where('user_id', $guest->id)->count();
            $dispatchCount = Dispatch::count();

            // Return the data in JSON format
            return response()->json([
                'reminderCount' => $reminderCount,
                'repairRequestCount' => $repairRequestCount,
                'mainCount' => $mainCount,
                'dispatchCount' => $dispatchCount,
                'name' => $guest->name,
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected errors
            return response()->json([
                'error' => 'An error occurred while fetching data.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    public function getLatestTicketByCar(Request $request)
    {
        $request->validate([
            "vehicles_id" => 'required'
        ]);

        $tripTicket = TripTicket::where('vehicles_id', $request->vehicles_id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$tripTicket) {
            return response()->json([
                "error" => "No trip ticket found for the given vehicle ID."
            ], 404);
        }

        $kbt = $tripTicket->KmAfterTravel;
        $totalFuelTank = $tripTicket->BalanceStart + $tripTicket->AddedDuringTrip + $tripTicket->IssuedFromOffice ;

        return response()->json([
            "kbt" => $kbt,
            "totalFuelTank" => $totalFuelTank
        ]);
    }




}
