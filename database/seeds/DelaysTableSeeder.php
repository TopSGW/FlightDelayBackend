<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DelaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('delays')->insert([
            'translation_code' => 'delays.2hours',
            'name' => '2 hours',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        DB::table('delays')->insert([
            'translation_code' => 'delays.4hours',
            'name' => '4 hours',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        DB::table('delays')->insert([
            'translation_code' => 'delays.6hours',
            'name' => '6 hours',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
    }
}
