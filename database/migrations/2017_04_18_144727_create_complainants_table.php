<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplainantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complainants', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('claim_id')->unsigned()
                  ->comment('The identification for the claim that contains the flight.');

            $table->integer('country_id')->unsigned()->nullable()->comment('The country the person lives in.');

            $table->integer('salutation_id')->unsigned()
                  ->comment('The salutation as used in letters and emails for the person.');

            $table->string('language', 2)
                  ->comment('The language the person used during the registration of the claim.');

            $table->string('first_name')->comment('The first name for the person.');
            $table->string('last_name')->comment('The last name for the person.');


            $table->string('postal_code')->comment('The postal code for the city the person lives in.');
            $table->string('city')->comment('The city the person lives in.');
            $table->string('street')->comment('The street the person lives in.');
            $table->string('house_number')->comment('The house number within the street the person lives in.');
            $table->string('box_number')->nullable()
                  ->comment('The box number within the street the person lives in.');

            $table->string('email')->comment('The e-mail address for the person.');

            $table->string('phone_number')->comment('The phone number for the person.');

            $table->text('remarks')->nullable()->comment('The remarks the claimant added to the claim.');

            $table->timestamps();

            $table->foreign('claim_id', 'fk_complainants_claim_id')
                  ->references('id')
                  ->on('claims')
                  ->onDelete('cascade');

            $table->foreign('country_id', 'fk_complainants_country_id')
                  ->references('id')
                  ->on('countries')
                  ->onDelete('cascade');

            $table->foreign('salutation_id', 'fk_complainants_salutation_id')
                  ->references('id')
                  ->on('salutations')
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
        Schema::dropIfExists('complainants');
    }
}
