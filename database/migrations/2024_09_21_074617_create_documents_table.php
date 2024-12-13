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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicles_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('reminders_id')->constrained('reminders')->cascadeOnDelete();
            $table->string('DocumentType');
            $table->string('DocumentNumber')->nullable();
            $table->date('IssueDate');
            $table->date('ExpirationDate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
