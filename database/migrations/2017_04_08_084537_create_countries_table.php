<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->char('iso_code', 2)->comment('2 character long iso code for the country.');
            $table->string('name', 50)->comment('English name for the country.');
            $table->integer('continent_id')->unsigned()->comment('Foreign key to the continent for the country.');
            $table->integer('source_id')->unsigned()
                  ->comment('Identifier for the country as found in te source. Can be used to update using the original datasets');
            $table->timestamps();

            $table->foreign('continent_id', 'fk_countries_continent_id')
                  ->references('id')
                  ->on('continents')
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
        Schema::dropIfExists('countries');
    }
}
