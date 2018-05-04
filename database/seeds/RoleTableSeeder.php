<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->insert([
            'name' => 'TYPE_SYSTEM_ADMIN',
            'value' => 99,
            'created_at'=> new \DateTime(),
            'updated_at'=> new \DateTime()
        ]);

        DB::table('role')->insert([
            'name' => 'TYPE_CLIENT_ADMIN',
            'value' => 49,
            'created_at'=> new \DateTime(),
            'updated_at'=> new \DateTime(),
        ]);

        DB::table('role')->insert([
            'name' => 'TYPE_CLIENT_USER',
            'value' => 29,
            'created_at'=> new \DateTime(),
            'updated_at'=> new \DateTime(),
        ]);

    }
}
