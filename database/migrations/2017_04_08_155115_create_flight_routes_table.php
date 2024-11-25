<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flight_routes', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('airline_id')->unsigned()->nullable()
                  ->comment('Identifier for the airline that flies the route');

            $table->integer('source_airport_id')->unsigned()->nullable()
                  ->comment('Identifier for the departure airport for the flight');

            $table->integer('destination_airport_id')->unsigned()->nullable()
                  ->comment('Identifier for the destination airport for the flight');

            $table->string('code_share')->nullable()
                  ->comment('"Y" if this flight is a codeshare (that is, not operated by Airline, but another carrier), empty otherwise.');

            $table->integer('stops')->nullable()
                  ->comment('Number of stops on this flight ("0" for direct).');

            $table->string('equipment')->nullable()
                  ->comment('3-letter codes for plane type(s) generally used on this flight, separated by space.');

            $table->integer('source_airline_id')->unsigned()->nullable()
                  ->comment('Identifier for the flight route as found in te source. Can be used to update using the original datasets');
            $table->integer('source_source_airport_id')->unsigned()->nullable()
                  ->comment('Identifier for the source airport as found in te source. Can be used to update using the original datasets');
            $table->integer('source_destination_airport_id')->unsigned()->nullable()
                  ->comment('Identifier for the destination airport as found in te source. Can be used to update using the original datasets');

            $table->timestamps();

            $table->foreign('airline_id', 'fk_flight_routes_airline_id')
                  ->references('id')
                  ->on('airlines')
                  ->onDelete('cascade');

            $table->foreign('source_airport_id', 'fk_flight_routes_source_airport_id')
                  ->references('id')
                  ->on('airports')
                  ->onDelete('cascade');

            $table->foreign('destination_airport_id', 'fk_flight_routes_destination_airport_id')
                  ->references('id')
                  ->on('airports')
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
        Schema::dropIfExists('flight_routes');
    }
}
