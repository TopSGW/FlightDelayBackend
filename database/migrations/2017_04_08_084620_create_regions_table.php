<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('iso_code', 10)->comment('Iso code for the region.');
            $table->string('local_code', 5)->comment('Local code for the region.');
            $table->string('name', 75)->comment('English name for the region.');
            $table->integer('continent_id')->unsigned()->comment('Foreign key to the continent for the region.');
            $table->integer('country_id')->unsigned()->comment('Foreign key to the country for the region.');
            $table->integer('source_id')->unsigned()
                  ->comment('Identifier for the region as found in te source. Can be used to update using the original datasets');
            $table->timestamps();

            $table->foreign('continent_id', 'fk_regions_continent_id')
                  ->references('id')
                  ->on('continents')
                  ->onDelete('cascade');

            $table->foreign('country_id', 'fk_regions_country_id')
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
        Schema::dropIfExists('regions');
    }
}
