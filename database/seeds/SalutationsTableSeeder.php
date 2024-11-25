<?php

use Illuminate\Database\Seeder;

class SalutationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('salutations')->insert([
            'translation_code' => 'salutations.mr',
            'name' => 'Mr.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        DB::table('salutations')->insert([
            'translation_code' => 'salutations.mrs',
            'name' => 'Mrs.',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

    }
}
