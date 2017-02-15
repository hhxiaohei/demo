<?php

use Illuminate\Database\Seeder;

class ImgsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('imgs')->delete();
        
        \DB::table('imgs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => '羊',
                'img' => 'o_1b8tn7ag3s2v13mr165al081io07.jpeg',
                'created_at' => '2017-02-14 14:58:36',
                'updated_at' => '2017-02-14 15:08:42',
            ),
            1 => 
            array (
                'id' => 2,
                'title' => '111',
                'img' => 'o_1b901h7m8cav8v9bng14hm13sq7.jpg',
                'created_at' => '2017-02-15 12:47:25',
                'updated_at' => '2017-02-15 12:47:25',
            ),
        ));
        
        
    }
}