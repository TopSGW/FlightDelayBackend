<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimFileNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_file_numbers', function (Blueprint $table) {
            $table->increments('id');

            $table->timestamps();
        });

        if(env('DB_CONNECTION') === 'mysql') {
            // Set the auto increment value to 1000, so it can be used as an automatic file numbering.
            DB::statement('ALTER TABLE claim_file_numbers AUTO_INCREMENT=1000;');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claim_file_numbers');
    }
}
