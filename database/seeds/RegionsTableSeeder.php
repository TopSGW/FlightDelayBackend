<?php

use App\Continent;
use App\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = Reader::createFromPath(base_path() . '/database/data/ourairports/regions.csv');

        // Don't import the header row
        $csv->setOffset(1);

        $csv->each(function ($row) {
            $continent = Continent::where('code', $row[4])->first();
            $country = Country::where('iso_code', $row[5])->first();

            DB::table('regions')->insert([
                'iso_code' => $row[1],
                'local_code' => $row[2],
                'name' => $row[3],
                'continent_id' => $continent->id,
                'country_id' => $country->id,
                'source_id' => (int)$row[0],
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ]);

            return true;
        });
    }
}
