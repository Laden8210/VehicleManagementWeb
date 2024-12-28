<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\DispatchController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TripTicketController;
use App\Http\Controllers\FuelConsumptionController;
use App\Http\Controllers\RepairRequestController;
use App\Http\Controllers\ServiceDetailsController;
use App\Http\Controllers\MaintenanceRecommendationController;
use App\Http\Controllers\ServiceRecordsController;
use App\Http\Controllers\Api\V1\RegisterController;
use App\Http\Controllers\APIController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//Public Routes
Route::post('/login',[AuthController::class, 'login']);
Route::post('/register',[RegisterController::class, 'store']);

//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function()
{
    //User
    Route::get('/user',[AuthController::class, 'user']);

    Route::put('/user',[AuthController::class, 'update']);
    Route::post('/logout',[AuthController::class, 'logout']);

    //Vehicle Route
    Route::get('/vehicles', [VehicleController::class, 'index']); // List all vehicles
    Route::get('/vehicles/{id}', [VehicleController::class, 'show']); // Show a specific vehicle by ID
    Route::post('/vehicles', [VehicleController::class, 'store']); // Create a new vehicle
    Route::put('/vehicles/{id}', [VehicleController::class, 'update']); // Update a vehicle
    Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy']); // Delete a vehicle

    //Dispatch Route
    Route::get('/dispatches', [DispatchController::class, 'index']); // List all dispatches
    Route::get('/dispatches/{id}', [DispatchController::class, 'show']); // Show a specific dispatch by ID
    Route::post('/dispatches', [DispatchController::class, 'store']); // Create a new dispatch
    Route::put('/dispatches/{id}', [DispatchController::class, 'update']); // Update a dispatch
    Route::delete('/dispatches/{id}', [DispatchController::class, 'destroy']); // Delete a dispatch

    //Patient Route
    Route::get('/patients', [PatientController::class, 'index']); // List all patients
    Route::get('/patients/{id}', [PatientController::class, 'show']); // Show a specific patient by ID
    Route::post('/patients', [PatientController::class, 'store']); // Create a new patient
    Route::put('/patients/{id}', [PatientController::class, 'update']); // Update a patient
    Route::delete('/patients/{id}', [PatientController::class, 'destroy']); // Delete a patient

    //Trip Ticket Route
    Route::get('/trip_tickets', [TripTicketController::class, 'index']); // List all trip tickets
    Route::get('/trip_tickets/{id}', [TripTicketController::class, 'show']); // Show a specific trip ticket by ID
    Route::post('/trip_tickets', [TripTicketController::class, 'store']); // Create a new trip ticket
    Route::put('/trip_tickets/{id}', [TripTicketController::class, 'update']); // Update a trip ticket
    Route::delete('/trip_tickets/{id}', [TripTicketController::class, 'destroy']); // Delete a trip ticket

    //Fuel Consumption Route
    Route::get('/fuel_consumptions', [FuelConsumptionController::class, 'index']); // List all fuel consumptions
    Route::get('/fuel_consumptions/{id}', [FuelConsumptionController::class, 'show']); // Show a specific fuel consumption by ID
    Route::post('/fuel_consumptions', [FuelConsumptionController::class, 'store']); // Create a new fuel consumption
    Route::put('/fuel_consumptions/{id}', [FuelConsumptionController::class, 'update']); // Update a fuel consumption
    Route::delete('/fuel_consumptions/{id}', [FuelConsumptionController::class, 'destroy']); // Delete a fuel consumption

    //Repair Request Route
    Route::get('/repair_requests', [RepairRequestController::class, 'index']); // List all repair requests
    Route::get('/repair_requests/{id}', [RepairRequestController::class, 'show']); // Show a specific repair request by ID
    Route::post('/repair_requests', [RepairRequestController::class, 'store']); // Create a new repair request
    Route::put('/repair_requests/{id}', [RepairRequestController::class, 'update']); // Update a repair request
    Route::delete('/repair_requests/{id}', [RepairRequestController::class, 'destroy']); // Delete a repair request

    //Service Details Route
    Route::get('/service_details', [ServiceDetailsController::class, 'index']); // List all service details
    Route::get('/service_details/{id}', [ServiceDetailsController::class, 'show']); // Show a specific service detail by ID
    Route::post('/service_details', [ServiceDetailsController::class, 'store']); // Create a new service detail
    Route::put('/service_details/{id}', [ServiceDetailsController::class, 'update']); // Update a service detail
    Route::delete('/service_details/{id}', [ServiceDetailsController::class, 'destroy']); // Delete a service detail

    //Maintenance Recommendation Route
    Route::get('/maintenance_recommendations', [MaintenanceRecommendationController::class, 'index']); // List all maintenance recommendations
    Route::get('/maintenance_recommendations/{id}', [MaintenanceRecommendationController::class, 'show']); // Show a specific maintenance recommendation by ID
    Route::post('/maintenance_recommendations', [MaintenanceRecommendationController::class, 'store']); // Create a new maintenance recommendation
    Route::put('/maintenance_recommendations/{id}', [MaintenanceRecommendationController::class, 'update']); // Update a maintenance recommendation
    Route::delete('/maintenance_recommendations/{id}', [MaintenanceRecommendationController::class, 'destroy']); // Delete a maintenance recommendation

    //Service Records Route
    Route::get('/service_records', [ServiceRecordsController::class, 'index']); // List all service records
    Route::get('/service_records/{id}', [ServiceRecordsController::class, 'show']); // Show a specific service record by ID
    Route::post('/service_records', [ServiceRecordsController::class, 'store']); // Create a new service record
    Route::put('/service_records/{id}', [ServiceRecordsController::class, 'update']); // Update a service record
    Route::delete('/service_records/{id}', [ServiceRecordsController::class, 'destroy']); // Delete a service record


    // update url

    Route::get('retrieve-dispatch', [APIController::class, 'getDispatch']);
    Route::post('add-trip-ticket', [APIController::class, 'addTicket']);
    Route::get('get-trip-ticket', [APIController::class, 'getTicket']);
    Route::post('vehicles', [APIController::class, 'getVehicles']);
    Route::post('create-repair-request', [APIController::class, 'addRepairRequest']);
    Route::get('repair-requests', [APIController::class, 'getRepairRequest']);

    Route::get('reminder', [APIController::class, 'getReminder']);
    Route::get('maintenance-recommendations', [APIController::class, 'getMaintenanceRecommendations']);
    Route::get('driver', [APIController::class, 'getDriver']);
    Route::post('add-maintenance-recommendation', [APIController::class, 'addMaintenanceRecommendations']);
    Route::get('dashboard', [APIController::class, 'getData']);
    Route::get('getLatestTicketByCar', [APIController::class, 'getLatestTicketByCar']);

    Route::get('get_user', [APIController::class, 'getUser']);


});
Route::get('getPersonnel', [APIController::class, 'getPersonnel']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function() {
    return response ([
        'message' => 'Api is Working'
    ], 200);
});




