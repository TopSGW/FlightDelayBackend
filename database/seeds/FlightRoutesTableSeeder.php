<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FlightRoutesTableSeeder extends Seeder
{
    const INSERT_BATCH_SIZE = 500;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createTmpRoutesTable();

        $this->import();

        $this->insertRecords();

        Schema::dropIfExists('routes');
    }

    protected function createTmpRoutesTable()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->string('airline_iata_code')->nullable();
            $table->string('airline_id')->nullable();

            $table->string('departure_airport_iata_code')->nullable();
            $table->string('departure_airport_id')->nullable();

            $table->string('destination_airport_iata_code')->nullable();
            $table->string('destination_airport_id')->nullable();

            $table->string('code_share')->nullable();
            $table->string('stops')->nullable();
            $table->string('equipment')->nullable();
        });
    }

    protected function import()
    {
        $file = base_path() . '/database/data/openflights/routes.dat';
        $user = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $database =  'homestead'; // config('database.connections.mysql.database');

        exec("mysqlimport --ignore-lines=0 --fields-terminated-by=, --local -u$user -p$password $database $file");
    }

    protected function insertRecords()
    {
        DB::insert('
          INSERT INTO flight_routes (
              airline_id, source_airport_id, destination_airport_id, code_share, stops,
              equipment, source_airline_id, source_source_airport_id, source_destination_airport_id, 
              created_at)
          SELECT  
            (SELECT id FROM airlines a WHERE a.iata_code = r.airline_iata_code LIMIT 1) AS airline_id,
            (SELECT id FROM airports ap WHERE ap.iata_code = r.departure_airport_iata_code LIMIT 1) AS source_airport_id,
            (SELECT id FROM airports ap WHERE ap.iata_code = r.destination_airport_iata_code LIMIT 1) AS destination_airport_id,
            NULL, -- CASE WHEN r.code_share = 0 OR r.code_share = "\N" THEN null ELSE r.code_share END,
            NULL, -- r.stops,
            NULL, -- CASE WHEN r.equipment = 0 OR r.equipment = "\N" THEN null ELSE r.equipment END AS equipment,
            CASE WHEN r.airline_id = "\N" THEN null ELSE r.airline_id END AS source_airline_id,
            CASE WHEN r.departure_airport_id = "\N" THEN null ELSE r.departure_airport_id END AS source_source_airport_id,
            CASE WHEN r.destination_airport_id = "\N" THEN null ELSE r.destination_airport_id END AS source_destination_airport_id,
            NOW() AS created_at
          FROM routes r
        ');
    }
}
