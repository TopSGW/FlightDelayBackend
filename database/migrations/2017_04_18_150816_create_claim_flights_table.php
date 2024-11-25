<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_flights', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('claim_id')->unsigned()
                  ->comment('The identification for the claim that contains the flight.');

            $table->integer('departure_airport_id')->unsigned()->nullable()
                  ->comment('The identification for the departure airport for the flight, if the flight number is unknown.');

            $table->integer('destination_airport_id')->unsigned()->nullable()
                  ->comment('The identification for the destination airport for the flight, if the flight number is unknown.');

            $table->integer('airline_id')->unsigned()->nullable()
                  ->comment('The identification for the airline company that flew the flight, if the flight number is unknown.');

            $table->integer('delay_id')->unsigned()->nullable()->comment('The delay for the flight.');

            $table->string('flight_number')->nullable()
                  ->comment('The flight number for the flight, if known.');

            $table->date('flight_date')->comment('The date for the flight.');

            $table->boolean('is_initial_flight')->default(true)
                  ->comment('Indicates whether this flight is the initial flight');

            $table->integer('flight_order')
                  ->comment('Sets the sequence for the flight if the claim has multiple flights');

            $table->timestamps();

            $table->foreign('claim_id', 'fk_claim_flights_claim_id')
                  ->references('id')
                  ->on('claims')
                  ->onDelete('cascade');

            $table->foreign('delay_id', 'fk_claim_flights_delay_id')
                  ->references('id')
                  ->on('delays')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claim_flights');
    }
}
