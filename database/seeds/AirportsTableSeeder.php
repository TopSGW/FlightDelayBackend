<?php

use App\Continent;
use App\Country;
use App\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class AirportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = Reader::createFromPath(base_path() . '/database/data/ourairports/airports.csv');

        // Don't import the header row
        $csv->setOffset(1);

        $csv->each(function ($row) {
            if ($row[2] == 'medium_airport' || $row[2] == 'large_airport') {
                $continent = Continent::where('code', $row[7])->first();
                $country = Country::where('iso_code', $row[8])->first();
                $region = Region::where('iso_code', $row[9])->first();

                DB::table('airports')->insert([
                    'identification' => $row[1],
                    'type' => $row[2],
                    'name' => $row[3],
                    'latitude' => (float)$row[4],
                    'longitude' => (float)$row[5],
                    'continent_id' => $continent->id,
                    'country_id' => $country->id,
                    'region_id' => $region->id,
                    'municipality' => strlen(trim($row[10])) === 0 || trim($row[10]) === '\N' ? null : trim($row[10]),
                    'gps_code' => strlen(trim($row[12])) === 0 || trim($row[12]) === '\N' ? null : trim($row[12]),
                    'iata_code' => strlen(trim($row[13])) === 0 || trim($row[13]) === '\N' ? null : trim($row[13]),
                    'source_id' => (int)$row[0],
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                ]);
            }

            return true;
        });
    }
}
