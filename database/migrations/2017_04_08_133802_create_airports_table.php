<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identification')->comment('Identification code for the airport.');
            $table->string('type')
                  ->comment('Indicates the size of the airport (only large and medium airports are used).');
            $table->string('name')->comment('English (or local) name for the airport.');
            $table->float('latitude')->comment('Latitude coordinates for the airport.');
            $table->float('longitude')->comment('Longitude coordinates for the airport.');
            $table->integer('continent_id')->unsigned()->comment('Foreign key to the continent for the airport.');
            $table->integer('country_id')->unsigned()->comment('Foreign key to the country for the airport.');
            $table->integer('region_id')->unsigned()->comment('Foreign key to the region for the airport.');
            $table->string('municipality')->nullable()->comment('Name of the municipality for the airport.');
            $table->string('gps_code', 4)->nullable()->comment('GPS code for the airport.');
            $table->string('iata_code', 3)->nullable()
                  ->comment('IATA (International Air Transport Association) code for the airport.');
            $table->integer('source_id')->unsigned()
                  ->comment('Identifier for the airport as found in te source. Can be used to update using the original datasets');
            $table->timestamps();

            $table->foreign('continent_id', 'fk_airports_continent_id')
                  ->references('id')
                  ->on('continents')
                  ->onDelete('cascade');

            $table->foreign('country_id', 'fk_airports_country_id')
                  ->references('id')
                  ->on('countries')
                  ->onDelete('cascade');

            $table->foreign('region_id', 'fk_airports_region_id')
                  ->references('id')
                  ->on('regions')
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
        Schema::dropIfExists('airports');
    }
}
