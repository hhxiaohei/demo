<?php

use Illuminate\Database\Seeder;

class FormTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('form')->delete();
        
        \DB::table('form')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => '2333',
                'type' => 0,
                'time' => '2017-02-14 00:00:00',
                'notetime' => '0000-00-00 00:00:00',
                'column' => '古文',
                'level' => 5,
                'contents' => '<p>33333</p>',
                'note' => '1111',
                'created_at' => '2017-02-14 12:02:10',
                'updated_at' => '2017-02-14 17:07:47',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => '1213',
                'type' => 0,
                'time' => '2017-02-15 00:00:00',
                'notetime' => '2017-02-14 17:29:00',
                'column' => 'hh,aa,测试,新测试',
                'level' => 2,
                'contents' => '<p>222</p>',
                'note' => '22222',
                'created_at' => '2017-02-14 16:43:19',
                'updated_at' => '2017-02-15 15:25:50',
            ),
        ));
        
        
    }
}