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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->string('Name');
            $table->string('Suffix')->nullable();
            $table->date('DateOfBirth');
            $table->integer('Age')->nullable();
            $table->string('Gender');
            $table->string('CivilStatus');
            $table->string('MobileNumber', 15)->unique();
            $table->string('EmailAddress')->email()->unique();
            $table->string('Address');
            $table->string('EmployeeID')->unique();
            $table->string('Designation');
            $table->string('Status');
            $table->string('Section');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnels');
    }
};
