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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('PatientName');
            $table->string('Gender');
            $table->string('Age');
            $table->string('PatientNumber')->unique();
            $table->string('PatientAddress');
            $table->string('PatientDiagnosis');
            $table->foreignId('dispatches_id')->constrained('dispatches')->cascadeOnDelete();
            $table->foreignId('trip_tickets_id')->constrained('trip_tickets')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
