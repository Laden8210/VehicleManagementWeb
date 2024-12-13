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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_requests_id')->nullable()->constrained('repair_requests')->cascadeOnDelete();
            $table->foreignId('maintenance_recommendations_id')->nullable()->constrained('maintenance_recommendations')->cascadeOnDelete();
            $table->date('RepairMaintenanceDate');
            $table->string('Description')->nullable();
            $table->decimal('AppropriationBudget', 10, 2)->nullable();
            $table->decimal('TotalCost', 10, 2)->nullable();
            $table->decimal('AppropriationBalance', 10, 2)->nullable();
            $table->string('PaymentType');
            $table->string('PaymentStatus');
            $table->string('DvNumber')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
