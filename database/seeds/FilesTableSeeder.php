<?php

use Illuminate\Database\Seeder;

class FilesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('files')->delete();
        
        \DB::table('files')->insert(array (
            0 => 
            array (
                'id' => 1,
                'title' => '1',
                'file' => 'o_1b8tpqucgc8u1buo1e8q13i71od5c.jpg|o_1b8tpri2n156i1g191jli1sbdpfi7.jpeg',
                'created_at' => '2017-02-14 15:54:22',
                'updated_at' => '2017-02-14 15:54:42',
            ),
        ));
        
        
    }
}