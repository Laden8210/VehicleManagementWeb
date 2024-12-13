<?php

use App\Http\Controllers\FuelConsumptionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaintenanceRecommendationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RepairRequestController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\TripTicketController;
use App\Models\FuelConsumption;
use Filament\Notifications\Events\DatabaseNotificationsSent;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('reminder/acknowledge/{id}', [NotificationController::class, 'acknowledge'])->name('reminder.acknowledge');


Route::resource('tripTickets', TripTicketController::class);
Route::get('repair_requests/{id}/print', [RepairRequestController::class, 'print'])->name('repair_requests.print');
Route::get('maintenance_recommendations/{id}/print', [MaintenanceRecommendationController::class, 'print'])->name('maintenance_recommendations.print');
Route::get('trip_tickets/{id}/print', [TripTicketController::class, 'print'])->name('trip_tickets.print');
Route::get('borrower_requests/{id}/print', [RequestController::class, 'print'])->name('borrower_requests.print');
Route::get('fuel_consumptions/{id}/print', [FuelConsumptionController::class, 'print'])->name('fuel_consumptions.print');
Route::get('fuel_slips/{id}/print', [FuelConsumptionController::class, 'printFuelSlip'])->name('fuel_slips.print');
Route::get('borrower_requests/{id}/print', [RequestController::class, 'print'])->name('borrower_requests.print');

// Route for printing monthly fuel consumption records
Route::get('/fuel-consumptions/print/{year}/{month}', [FuelConsumptionController::class, 'print'])
    ->name('fuel_consumptions.print_monthly');



Route::patch('/repair-requests/{repairRequest}/status', [RepairRequestController::class, 'updateStatus']);
Route::patch('/maintenance-recommendations/{maintenanceRecommendation}/status', [MaintenanceRecommendationController::class, 'updateStatus']);






