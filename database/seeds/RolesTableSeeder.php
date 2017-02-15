<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'admin',
                'display_name' => '超级管理员',
                'description' => NULL,
                'created_at' => '2016-12-15 14:42:46',
                'updated_at' => '2016-12-15 14:42:46',
            ),
            1 => 
            array (
                'id' => 4,
                'name' => 'test',
                'display_name' => '测试组',
                'description' => '测试组',
                'created_at' => '2016-12-20 00:25:54',
                'updated_at' => '2016-12-20 00:25:54',
            ),
        ));
        
        
    }
}