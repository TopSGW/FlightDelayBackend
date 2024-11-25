<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airlines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('Local name for the airline.');
            $table->char('iata_code', 2)->nullable()
                  ->comment('2 character IATA (International Air Transport Association) code for the airline, if available.');
            $table->char('icao_code', 3)->nullable()
                  ->comment('3 character ICAO () code for the airline, if available.');
            $table->string('call_sign')->nullable()
                  ->comment('The callsign for the airline.');
            $table->string('country')->nullable()
                  ->comment('Country or territory where the airline is incorporated.');
            $table->integer('country_id')->unsigned()->nullable()
                  ->comment('Foreign key to the country for the airline.');

            $table->integer('source_id')->unsigned()
                  ->comment('Identifier for the airline as found in te source. Can be used to update using the original datasets');
            $table->timestamps();

            $table->foreign('country_id', 'fk_airlines_country_id')
                  ->references('id')
                  ->on('countries')
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
        Schema::dropIfExists('airlines');
    }
}
