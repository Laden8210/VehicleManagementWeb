<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripTicketResponderTable extends Migration
{
    public function up()
    {
        Schema::create('trip_ticket_responder', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_ticket_id')->constrained('trip_tickets')->onDelete('cascade');
            $table->foreignId('responder_id')->constrained('personnels')->onDelete('cascade');
            $table->string('role')->default('Responder');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_ticket_responder');
    }
}


