<?php

use Illuminate\Database\Seeder;

class MigrationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('migrations')->delete();
        
        \DB::table('migrations')->insert(array (
            0 => 
            array (
                'migration' => '2014_10_12_000000_create_admins_table',
                'batch' => 1,
            ),
            1 => 
            array (
                'migration' => '2014_10_12_000000_create_users_table',
                'batch' => 1,
            ),
            2 => 
            array (
                'migration' => '2014_10_12_100000_create_password_resets_table',
                'batch' => 1,
            ),
            3 => 
            array (
                'migration' => '2014_10_12_100000_create_roles_table',
                'batch' => 1,
            ),
            4 => 
            array (
                'migration' => '2014_10_13_100000_create_permissions_table',
                'batch' => 1,
            ),
            5 => 
            array (
                'migration' => '2014_10_13_100000_create_role_user_table',
                'batch' => 1,
            ),
            6 => 
            array (
                'migration' => '2014_10_14_100000_create_permission_role_table',
                'batch' => 1,
            ),
            7 => 
            array (
                'migration' => '2016_12_15_073604_create_categories_table',
                'batch' => 2,
            ),
            8 => 
            array (
                'migration' => '2016_12_15_074102_create_categories_table_2',
                'batch' => 3,
            ),
            9 => 
            array (
                'migration' => '2016_12_15_082825_add_type_id_risk_level_for_permissions',
                'batch' => 4,
            ),
            10 => 
            array (
                'migration' => '2016_12_16_102827_add_sex_to_table_permissions',
                'batch' => 5,
            ),
            11 => 
            array (
                'migration' => '2016_12_16_103806_change_sex_to_table_permissions',
                'batch' => 6,
            ),
            12 => 
            array (
                'migration' => '2016_12_16_135456_add_department_table_permissions',
                'batch' => 7,
            ),
            13 => 
            array (
                'migration' => '2016_12_19_121837_add_status_table_permissions',
                'batch' => 8,
            ),
            14 => 
            array (
                'migration' => '2017_02_14_110643_table_form',
                'batch' => 9,
            ),
            15 => 
            array (
                'migration' => '2016_09_18_085507_create_operation_logs_table',
                'batch' => 10,
            ),
            16 => 
            array (
                'migration' => '2017_02_14_130025_create_table_datas',
                'batch' => 11,
            ),
            17 => 
            array (
                'migration' => '2017_02_14_135413_create_table_imgs',
                'batch' => 12,
            ),
            18 => 
            array (
                'migration' => '2017_02_14_152005_create_table_files',
                'batch' => 13,
            ),
        ));
        
        
    }
}