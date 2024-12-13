<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trip_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('TripTicketNumber');
            $table->date('ArrivalDate');
            $table->date('ReturnDate');
            $table->foreignId('vehicles_id')->constrained('vehicles')->cascadeOnDelete();
            $table->string('Origin');
            $table->string('Destination');
            $table->string('Purpose');
            $table->integer('KmBeforeTravel');
            $table->integer('KmAfterTravel')->nullable();
            $table->integer('DistanceTravelled')->nullable();
            $table->time('TimeDeparture_A')->nullable();
            $table->time('TimeArrival_A')->nullable();
            $table->time('TimeDeparture_B')->nullable();
            $table->time('TimeArrival_B')->nullable();
            $table->decimal('BalanceStart', 10, 2)->nullable();
            $table->decimal('IssuedFromOffice', 10, 2)->nullable();
            $table->decimal('AddedDuringTrip', 10, 2)->nullable();
            $table->decimal('TotalFuelTank', 10, 2)->nullable();
            $table->decimal('FuelConsumption', 10, 2)->nullable();
            $table->decimal('BalanceEnd', 10, 2)->nullable();
            $table->string('Others')->nullable();
            $table->string('Remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_tickets');
    }
};
