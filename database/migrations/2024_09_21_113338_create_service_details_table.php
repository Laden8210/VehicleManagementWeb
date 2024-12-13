<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_requests_id')->constrained('repair_requests')->cascadeOnDelete();
            $table->foreignId('suppliers_id')->constrained('suppliers')->cascadeOnDelete();
            $table->date('RepairDate');
            $table->string('RepairType')->nullable();
            $table->string('ServiceDescription');
            $table->string('ChangedParts');
            $table->decimal('ServiceCosts', 10, 2)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicedetails');
    }
};
