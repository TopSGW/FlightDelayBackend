<?php

use App\Country;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class AirlinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = Reader::createFromPath(base_path() . '/database/data/openflights/airlines.dat');

        // Don't import the first row
        $csv->setOffset(2);

        $csv->each(function ($row) {
            $country = Country::where('name', $row[6])->first();

            if ($row[7] !== 'N') {
                $iataCode = $this->filterInvalidCharacters($row[3]);
                $icaoCode = $this->filterInvalidCharacters($row[4]);

                DB::table('airlines')->insert([
                    'name' => $row[1],
                    'iata_code' => strlen($iataCode) === 0 || $iataCode === '\N' ? null : $iataCode,
                    'icao_code' => strlen($icaoCode) === 0 || $icaoCode === '\N' ? null : $icaoCode,
                    'call_sign' => strlen(trim($row[5])) === 0 || trim($row[5]) === '\N' ? null : trim($row[5]),
                    'country' => strlen(trim($row[6])) === 0 || trim($row[6]) === '\N' ? null : trim($row[6]),
                    'country_id' => $country === null ? null : $country->id,
                    'source_id' => (int)$row[0],
                    'created_at' => Carbon::now()->toDateTimeString(),
                ]);
            }

            return true;
        });
    }

    private function filterInvalidCharacters($value)
    {
        $filteredValue = str_replace("\\'", null, $value);

        return trim(str_replace("\\'\\", null, $filteredValue));
    }
}
