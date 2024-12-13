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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowers_id')->constrained('borrowers')->cascadeOnDelete();
            $table->string('GTNumber')->unique()->nullable();
            $table->foreignId('inventories_id')->constrained('inventories')->cascadeOnDelete();
            $table->integer('Quantity')->nullable();
            $table->integer('NumberOfItems');
            $table->date('RequestDate');
            $table->date('ReturnDate');
            $table->string('Purpose');
            $table->string('RequestStatus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
