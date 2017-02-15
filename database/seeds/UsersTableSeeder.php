<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Prof. Tobin Rosenbaum MD',
                'email' => 'cormier.hillard@example.net',
                'password' => '$2y$10$mEYi7PdaJqaCnB.iMV7ge.1tnjysJfBCQ3/5lp78HFhWQALqTMvRS',
                'remember_token' => 'FV8Y1i0Ppq',
                'created_at' => '2017-02-14 10:59:39',
                'updated_at' => '2017-02-14 10:59:39',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Gaylord Hilll',
                'email' => 'keenan37@example.org',
                'password' => '$2y$10$Slok8BWf19SsVYoNw9b0NehXc626cS.FJrzOU5j5bgArmKyCiqs/i',
                'remember_token' => 'O3OzWpkOPY',
                'created_at' => '2017-02-14 10:59:39',
                'updated_at' => '2017-02-14 10:59:39',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Jaquan Hirthe',
                'email' => 'stark.lew@example.net',
                'password' => '$2y$10$m6FJZzANbdGyi.uVGjSF2OVZZ8lXyRJUHNkp57YorL8wUWoFQTw96',
                'remember_token' => 'FrcAdbgZra',
                'created_at' => '2017-02-14 10:59:39',
                'updated_at' => '2017-02-14 10:59:39',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Yvette Goyette',
                'email' => 'xrunolfsson@example.net',
                'password' => '$2y$10$XuShnPcWpVmBYPipJblUmO50yKZBfoCvMYre22CS1HW0BYQPnRT1a',
                'remember_token' => 'UIyHw6viOU',
                'created_at' => '2017-02-14 10:59:39',
                'updated_at' => '2017-02-14 10:59:39',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Chaz Eichmann',
                'email' => 'madilyn03@example.org',
                'password' => '$2y$10$jIv0qI1BK1XRvGI/QYL4gu.42gGlaDM91XCjveMNw1py4L.vh3Xyu',
                'remember_token' => '6OcTO3KG0Q',
                'created_at' => '2017-02-14 10:59:39',
                'updated_at' => '2017-02-14 10:59:39',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Prof. Alek Mohr',
                'email' => 'kzboncak@example.net',
                'password' => '$2y$10$h8nMKh5a4NVrPRVhYJusCe31/5s.9UMXXZZzZ8Jrn199.JfAfhcOG',
                'remember_token' => 'e5T2NE9A0u',
                'created_at' => '2017-02-14 10:59:39',
                'updated_at' => '2017-02-14 10:59:39',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Prof. Jaylin Bahringer III',
                'email' => 'willms.anna@example.net',
                'password' => '$2y$10$LBYjbpjkF41LPs8EAl9uB.Wud9cLUIFR6gFvq8lSCSIqGWH1nevhi',
                'remember_token' => 'SQrCVxjfdU',
                'created_at' => '2017-02-14 10:59:39',
                'updated_at' => '2017-02-14 10:59:39',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Zelda Pacocha I',
                'email' => 'olubowitz@example.net',
                'password' => '$2y$10$jzCof/MluNnS7CrLhaMcpumcPPqZSCIM4yVEUAVIfT7n1WBTMCQb.',
                'remember_token' => 'cY1VZQxcfX',
                'created_at' => '2017-02-14 10:59:39',
                'updated_at' => '2017-02-14 10:59:39',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Melissa Larson',
                'email' => 'lempi.lueilwitz@example.net',
                'password' => '$2y$10$1Ij8yEVd4fjlhMwIhbK0ce/Zu./NktOQAQ9UEShBBajt8yAh38EiW',
                'remember_token' => 'QzUZlvEqxt',
                'created_at' => '2017-02-14 10:59:39',
                'updated_at' => '2017-02-14 10:59:39',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Mercedes Toy',
                'email' => 'wilkinson.valentin@example.net',
                'password' => '$2y$10$ZCbIhZaymUxzrlZfrHnKfOy8T4xyRvGHXCRWhiMpBlHhD3mV6EfPW',
                'remember_token' => 'm89uBKqKe8',
                'created_at' => '2017-02-14 10:59:39',
                'updated_at' => '2017-02-14 10:59:39',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Dejon Buckridge',
                'email' => 'kertzmann.arlene@example.net',
                'password' => '$2y$10$FkcfPE70Hk7HnipRS9D58O.6A8.wM4.JnURz/HJOS96s06HDEYYIm',
                'remember_token' => 'eCrdR76C37',
                'created_at' => '2017-02-14 10:59:47',
                'updated_at' => '2017-02-14 10:59:47',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Jaron Bernhard',
                'email' => 'lowe.marguerite@example.net',
                'password' => '$2y$10$rEwVEyoNLuMvGT4udAJIMOtoVUcwXfLguN5Lv/AIM6HCmKzjJYP0.',
                'remember_token' => 'lDboNJj90D',
                'created_at' => '2017-02-14 10:59:47',
                'updated_at' => '2017-02-14 10:59:47',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Winona Sawayn',
                'email' => 'mathew73@example.net',
                'password' => '$2y$10$kvZy3sYnIiugIpzbi4LpI./wAZaCQbj9nLezK9dgwRf5iBcTt8VtC',
                'remember_token' => 'xC42YYCi7G',
                'created_at' => '2017-02-14 10:59:47',
                'updated_at' => '2017-02-14 10:59:47',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Gretchen Rogahn',
                'email' => 'christine.frami@example.com',
                'password' => '$2y$10$107wYzgl2n53eZ8sitQDfu0OP5W3nqChOweTrSMks0aiGMfVgolsW',
                'remember_token' => 'vvBXdVtvK6',
                'created_at' => '2017-02-14 10:59:47',
                'updated_at' => '2017-02-14 10:59:47',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Jerome Runte',
                'email' => 'aisha68@example.com',
                'password' => '$2y$10$/Qdd2KhdszWzyJqlYF9qJOoJ.y1deh1Da39Dnhu0TyLciPaT.vPEe',
                'remember_token' => 'N4J1cJRFZJ',
                'created_at' => '2017-02-14 10:59:47',
                'updated_at' => '2017-02-14 10:59:47',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Clifford Simonis',
                'email' => 'wlabadie@example.org',
                'password' => '$2y$10$YwjkjkuuYN8l1wP.o1jXE.ctiGrVTIvKJBsiNhDod7UmMuKCzRQ9O',
                'remember_token' => 'jicgAkX7cL',
                'created_at' => '2017-02-14 10:59:47',
                'updated_at' => '2017-02-14 10:59:47',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Mrs. Karlee Hagenes',
                'email' => 'nelson.waters@example.net',
                'password' => '$2y$10$AquTSR6AhS.9KS4qlp9TkONeWUBPaJV1h6Ke1KpKdiAHgksYC20xS',
                'remember_token' => 'rogsY07wpB',
                'created_at' => '2017-02-14 10:59:47',
                'updated_at' => '2017-02-14 10:59:47',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Guiseppe Leannon',
                'email' => 'francisca32@example.net',
                'password' => '$2y$10$I07nhDB1m5icHA5vr2DAuO25kQm95XIDGXYDlfcgb5W5Pfl8Dl/SC',
                'remember_token' => 'bHdNx4OPon',
                'created_at' => '2017-02-14 10:59:47',
                'updated_at' => '2017-02-14 10:59:47',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Baron Windler II',
                'email' => 'boyer.trinity@example.com',
                'password' => '$2y$10$wntZzR0OoKeohOGLSRqji.A69G.zFSwPV5ekb5KbrlNh8vPX2cGOK',
                'remember_token' => '9IyAk68DaT',
                'created_at' => '2017-02-14 10:59:47',
                'updated_at' => '2017-02-14 10:59:47',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Tomas Becker III',
                'email' => 'goldner.ross@example.net',
                'password' => '$2y$10$Xe.2XIyeNFwFTI7OG6r4weovd/L0la1d7lEhjuuWMkBwcMv/ZtX9K',
                'remember_token' => 'Va0vXFhfSI',
                'created_at' => '2017-02-14 10:59:47',
                'updated_at' => '2017-02-14 10:59:47',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Marianne Durgan',
                'email' => 'zhilll@example.net',
                'password' => '$2y$10$dlabWfz0wfpnAv0GseI.6OsOVN3JWWlm.NLnAMakXm5q32061NnXC',
                'remember_token' => 'sJIf8G6Rc2',
                'created_at' => '2017-02-14 10:59:52',
                'updated_at' => '2017-02-14 10:59:52',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'Daryl Shanahan',
                'email' => 'meda99@example.net',
                'password' => '$2y$10$lmZIZDLPID.NVX2h/T4CzOiS9h9rsHq5FRm8KYSjvqIr50ZBnA6ma',
                'remember_token' => 'T5ClHpnRZh',
                'created_at' => '2017-02-14 10:59:52',
                'updated_at' => '2017-02-14 10:59:52',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'Roel Vandervort',
                'email' => 'tremblay.cristopher@example.org',
                'password' => '$2y$10$EhWoWI4gKj.xI9xxYOPq2uNs.BtvNwbgii11gTwYhwkidCE9ux.2C',
                'remember_token' => 'el2wQhZzsr',
                'created_at' => '2017-02-14 10:59:52',
                'updated_at' => '2017-02-14 10:59:52',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'Bernita Herzog',
                'email' => 'haag.enrico@example.com',
                'password' => '$2y$10$dSnmOqSTlf8t7efHqPulxe8BdgZ71KS0pEMDhPBdYaDd8l4aeit1i',
                'remember_token' => '4qGTcZgRtk',
                'created_at' => '2017-02-14 10:59:52',
                'updated_at' => '2017-02-14 10:59:52',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'Kevin Stoltenberg Jr.',
                'email' => 'tokuneva@example.com',
                'password' => '$2y$10$rwZodATJTBy9SI6ezAtS8ud8kYCbZP5/aTXm96n1U6mc0qZduMxQu',
                'remember_token' => 'FUEFXWVP1w',
                'created_at' => '2017-02-14 10:59:52',
                'updated_at' => '2017-02-14 10:59:52',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'Jada Graham',
                'email' => 'owiza@example.com',
                'password' => '$2y$10$a7tKfpO2c7AW82XEk2IkeOXcMdC1l9OJYtDOKakz.8jnGU15wY.Ua',
                'remember_token' => 'YHFUqtNgXI',
                'created_at' => '2017-02-14 10:59:52',
                'updated_at' => '2017-02-14 10:59:52',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'Dasia Durgan',
                'email' => 'gjakubowski@example.net',
                'password' => '$2y$10$TWfYWvft/dsqecQGplQpwu8NYzWhJ7HWaEv8UGbcCGMDFMVXa72x6',
                'remember_token' => 'xy0Z7LHBHX',
                'created_at' => '2017-02-14 10:59:52',
                'updated_at' => '2017-02-14 10:59:52',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'Ms. Marcelina Quitzon PhD',
                'email' => 'adell00@example.com',
                'password' => '$2y$10$MSFryrOWupf95iTbuhhCguag8aEE/0943CU64UpHhnP8DvAMv6vHe',
                'remember_token' => 'K6lQn2ogpu',
                'created_at' => '2017-02-14 10:59:52',
                'updated_at' => '2017-02-14 10:59:52',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'Mallory Olson',
                'email' => 'schaefer.kareem@example.org',
                'password' => '$2y$10$9p/Yu1rg.zoSzatt0tG0i.7bR2e8AO.T3F2I/UEnU0cFxbZCsTtjm',
                'remember_token' => 'NGTCXGikYO',
                'created_at' => '2017-02-14 10:59:52',
                'updated_at' => '2017-02-14 10:59:52',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'Adonis Casper III',
                'email' => 'madaline53@example.org',
                'password' => '$2y$10$RuhNGOuhYT7kmNuYN2XOHObSasLf6KieWij21q3qpW.1LfjbS5Fq6',
                'remember_token' => 'ySXpaVt9pl',
                'created_at' => '2017-02-14 10:59:52',
                'updated_at' => '2017-02-14 10:59:52',
            ),
        ));
        
        
    }
}