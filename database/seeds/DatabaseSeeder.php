<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * @var array
     */
    private $tables = [
        'airlines',
        'airports',
        'claim_flights',
        'claim_types',
        'claim_file_numbers',
        'claims',
        'salutations',
        'complainants',
        'continents',
        'countries',
        'delays',
        'flight_routes',
        'regions',
        'users',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', "2048M");

        $this->cleanDatabase();

        $this->call(ClaimTypesTableSeeder::class);
        $this->call(DelaysTableSeeder::class);
        $this->call(SalutationsTableSeeder::class);

        if (App::environment('testing') === false) {
            $this->call(UsersTableSeeder::class);
            $this->call(ContinentsTableSeeder::class);
            $this->call(CountriesTableSeeder::class);
            $this->call(RegionsTableSeeder::class);
            $this->call(AirportsTableSeeder::class);
            $this->call(AirlinesTableSeeder::class);
            $this->call(FlightRoutesTableSeeder::class);
        }
    }

    private function cleanDatabase()
    {
        Schema::disableForeignKeyConstraints();

        foreach ($this->tables as $tableName) {
            DB::table($tableName)->truncate();

            if(env('DB_CONNECTION') === 'mysql') {
                $increment = 1;

                if ($tableName === 'claim_file_numbers') {
                    $increment = 1000;
                }

                DB::statement("ALTER TABLE `$tableName` AUTO_INCREMENT = $increment;");
            }
        }

        Schema::enableForeignKeyConstraints();
    }
}
