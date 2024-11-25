<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('claim_type_id')->unsigned()
                  ->comment('The identification type of claim.');

            $table->text('remarks')->nullable()->comment('The remarks the claimant added to the claim.');

            $table->text('source')->comment('The source of the claim (internet, partner, ...).');

            $table->text('file_number')->nullable()->comment('The file number for the claim.');

            $table->timestamps();

            $table->foreign('claim_type_id', 'fk_claims_claim_type_id')
                  ->references('id')
                  ->on('claim_types')
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
        Schema::dropIfExists('claims');
    }
}
