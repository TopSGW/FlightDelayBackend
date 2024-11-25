<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Michael',
            'email' => 'michael.kues@koneksa.be',
            'password' => '$2y$10$iFCxEIDqDggZs.yAiMrfFu4aQGnfjDOP27aMM0IDsGbBbHR8sb.lW',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        DB::table('users')->insert([
            'name' => 'Yassine',
            'email' => 'yassinesbaiti@hotmail.com',
            'password' => '$2y$10$6KJJTt2Io2ZiFuWoAjEGl.cG7ynHpPZsIDKQKB31ssVtdlbuPW0Se',
            'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);
    }
}
