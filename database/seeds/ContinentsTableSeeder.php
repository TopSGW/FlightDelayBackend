<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContinentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $continents = [
            ['code' => 'AF', 'name' => 'Africa'],
            ['code' => 'AN', 'name' => 'Antarctica'],
            ['code' => 'AS', 'name' => 'Asia'],
            ['code' => 'EU', 'name' => 'Europe'],
            ['code' => 'NA', 'name' => 'North America'],
            ['code' => 'OC', 'name' => 'Oceanica'],
            ['code' => 'SA', 'name' => 'South Africa'],
        ];

        foreach ($continents as $continent) {
            DB::table('continents')->insert([
                'code' => $continent['code'],
                'name' => $continent['name'],
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ]);
        }
    }
}
