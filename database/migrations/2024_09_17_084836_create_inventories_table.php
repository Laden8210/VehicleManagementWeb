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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('ItemName')->unique('');
            $table->string('ItemCode');
            $table->string('ItemDescription');
            $table->string('ItemUnit');
            $table->integer('ItemQuantity');
            $table->date('ExpirationDate')->nullable();
            $table->string('ItemStatus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
