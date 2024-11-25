<?php

use App\Continent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = Reader::createFromPath(base_path() . '/database/data/ourairports/countries.csv');

        // Don't import the header row
        $csv->setOffset(1);

        $csv->each(function ($row) {
            $continent = Continent::where('code', $row[3])->first();

            DB::table('countries')->insert([
                'iso_code' => $row[1],
                'name' => $row[2],
                'continent_id' => $continent->id,
                'source_id' => (int)$row[0],
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ]);

            return true;
        });
    }
}
