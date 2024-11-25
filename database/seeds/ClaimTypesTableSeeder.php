<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClaimTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('claim_types')->insert([
            'id' => 1,
            'translation_code' => 'claimTypes.delay',
            'name' => 'My flight had a delay',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        DB::table('claim_types')->insert([
            'id' => 2,
            'translation_code' => 'claimTypes.refused',
            'name' => 'I was refused while onboarding',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        DB::table('claim_types')->insert([
            'id' => 3,
            'translation_code' => 'claimTypes.flight-cancelled',
            'name' => 'My flight was cancelled',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
    }
}
