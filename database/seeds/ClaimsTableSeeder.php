<?php

use App\Claim;
use App\ClaimFlight;
use Illuminate\Database\Seeder;

class ClaimsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Claim::class, 1)
            ->create(['claim_type_id' => mt_rand(1, 3)])
            ->each(function ($claim) {
//                $claim->flights()->save(factory(ClaimFlight::class)->make(['is_initial_flight' => 1, 'flight_order' => 1]));
//                $claim->flights()->save(factory(ClaimFlight::class)->make(['is_initial_flight' => 0, 'flight_order' => 2]));
            });
    }
}