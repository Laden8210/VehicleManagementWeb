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
        Schema::create('repair_requests', function (Blueprint $table) {
            $table->id();
            $table->string('RRNumber')->unique()->nullable();
            $table->foreignId('vehicles_id')->constrained('vehicles')->cascadeOnDelete();
            $table->date('RequestDate');
            $table->string('ReportedIssue');
            $table->json('Issues');
            $table->string('PriorityLevel');
            $table->string('RequestStatus');
            $table->text('DisapprovalComments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_requests');
    }
};
