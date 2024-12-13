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
        Schema::create('fuel_consumptions', function (Blueprint $table) {
            $table->id();
            $table->string('WithdrawalSlipNo');
            $table->string('PONum')->nullable();
            $table->date('RequestDate');
            $table->string('ReferenceNumber')->nullable();
            $table->foreignId('trip_tickets_id')->constrained('trip_tickets')->cascadeOnDelete();
            $table->integer('Quantity')->nullable();
            $table->decimal('Price', 10, 2)->nullable();
            $table->decimal('Amount', 10, 2)->nullable();
            $table->decimal('PreviousBalance', 10, 2)->nullable();
            $table->decimal('RemainingBalance', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_consumptions');
    }
};
