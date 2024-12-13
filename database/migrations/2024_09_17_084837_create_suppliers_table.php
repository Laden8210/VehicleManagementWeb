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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('SupplierName');
            $table->string('ContactPerson');
            $table->string('Designation');
            $table->string('MobileNumber')->unique();
            $table->string('CompleteAddress');
            $table->string('EmailAddress')->email()->unique();
            $table->year('YearEstablished');
            $table->string('PhilgepsMembership');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
