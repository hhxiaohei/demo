<?php

use Illuminate\Database\Seeder;

class RoleAdminTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('role_admin')->delete();
        
        \DB::table('role_admin')->insert(array (
            0 => 
            array (
                'user_id' => 1,
                'role_id' => 1,
            ),
            1 => 
            array (
                'user_id' => 3,
                'role_id' => 4,
            ),
        ));
        
        
    }
}