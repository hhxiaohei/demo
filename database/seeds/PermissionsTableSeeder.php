<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'admin',
                'display_name' => '3',
                'description' => '22',
                'created_at' => '2016-12-15 14:42:46',
                'updated_at' => '2016-12-16 21:16:22',
            ),
            1 => 
            array (
                'id' => 6,
                'name' => '测试',
                'display_name' => '2',
                'description' => '测试',
                'created_at' => '2016-12-16 09:49:29',
                'updated_at' => '2016-12-16 09:51:04',
            ),
            2 => 
            array (
                'id' => 8,
                'name' => '测试3',
                'display_name' => '1',
                'description' => '测试3',
                'created_at' => '2016-12-16 17:53:33',
                'updated_at' => '2016-12-16 17:53:33',
            ),
            3 => 
            array (
                'id' => 9,
                'name' => '测试4',
                'display_name' => '1',
                'description' => '测试4',
                'created_at' => '2016-12-16 17:57:35',
                'updated_at' => '2016-12-16 17:57:35',
            ),
            4 => 
            array (
                'id' => 10,
                'name' => '测试5',
                'display_name' => '1',
                'description' => '测试5',
                'created_at' => '2016-12-16 17:57:55',
                'updated_at' => '2016-12-16 17:57:55',
            ),
            5 => 
            array (
                'id' => 11,
                'name' => '测试6',
                'display_name' => '2',
                'description' => '测试6',
                'created_at' => '2016-12-16 17:58:08',
                'updated_at' => '2016-12-16 19:01:09',
            ),
            6 => 
            array (
                'id' => 13,
                'name' => '21212222',
                'display_name' => '1',
                'description' => '测试2',
                'created_at' => '2016-12-16 22:33:53',
                'updated_at' => '2016-12-19 21:27:49',
            ),
            7 => 
            array (
                'id' => 14,
                'name' => '2121',
                'display_name' => '2',
                'description' => '2121',
                'created_at' => '2016-12-19 18:08:29',
                'updated_at' => '2016-12-20 00:48:27',
            ),
        ));
        
        
    }
}