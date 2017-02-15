<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(MigrationsTableSeeder::class);
        $this->call(PasswordResetsTableSeeder::class);
        $this->call(FormTableSeeder::class);
        $this->call(DatasTableSeeder::class);
        $this->call(ImgsTableSeeder::class);
        $this->call(FilesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(OperationLogsTableSeeder::class);
        $this->call(RoleAdminTableSeeder::class);
    }
}
