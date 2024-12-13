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
        Schema::create('maintenance_recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('MRNumber')->unique()->nullable();
            $table->foreignId('vehicles_id')->constrained('vehicles')->cascadeOnDelete();
            $table->string('RecommendationType');
            $table->json('Issues');
            $table->date('RecommendationDate');
            $table->date('DueDate');
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
        Schema::dropIfExists('maintenance_recommendations');
    }
};
