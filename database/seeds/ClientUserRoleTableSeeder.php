<?php

use Illuminate\Database\Seeder;

class ClientUserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('client_user_role')->insert([
            'client_id' => 1,
            'user_id' => 2,
            'role_id' => 2,
            'default' => true,
            'created_at'=> new \DateTime(),
            'updated_at'=> new \DateTime()
        ]);

        DB::table('client_user_role')->insert([
            'client_id' => 2,
            'user_id' => 3,
            'role_id' => 2,
            'default' => true,
            'created_at'=> new \DateTime(),
            'updated_at'=> new \DateTime()
        ]);
    }
}
