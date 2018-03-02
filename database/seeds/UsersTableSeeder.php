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
            'name' => 'admin@localhost',
            'email' => 'admin@localhost',
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'type' => 99,
            'password' => bcrypt('secret'),
        ]);
    }
}
