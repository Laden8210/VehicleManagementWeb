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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('VehicleName')->unique();
            $table->string('MvfileNo')->unique();
            $table->string('PlateNumber')->unique();
            $table->string('EngineNumber');
            $table->string('ChassisNumber');
            $table->string('Fuel');
            $table->string('Make');
            $table->string('Series');
            $table->string('BodyType');
            $table->year('YearModel');
            $table->string('Color');
            $table->string('PurchasedDate')->nullable();
            $table->string('RegistrationDate')->nullable();
            $table->string('OrcrNo')->nullable();
            $table->string('PurchasedCost')->nullable();
            $table->string('PropertyNumber')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
