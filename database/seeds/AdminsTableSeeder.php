<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admins')->delete();
        
        \DB::table('admins')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '超级管理员',
                'email' => 'admin@admin.com',
                'password' => '$2y$10$ls04EGfkve/MI7hILreCNeTHv27lqn1.EvlSu9RvgqL7cc5m5gYOO',
                'remember_token' => 'r4fPWH54NL9KvO89srKF6jwpD8nEkhSk3EPpQzCqvS9qjNOYHdnSkSeFFJ8C',
                'created_at' => '2016-12-15 14:42:46',
                'updated_at' => '2017-02-13 21:44:15',
            ),
            1 => 
            array (
                'id' => 3,
                'name' => '测试',
                'email' => '11@qq.com',
                'password' => '$2y$10$/zV.qFzud7Vh6Rg5x.RHXOVObQnOg3EDGQpjXRQBHii30x1uLUxee',
                'remember_token' => NULL,
                'created_at' => '2016-12-20 00:23:24',
                'updated_at' => '2016-12-20 00:23:24',
            ),
        ));
        
        
    }
}