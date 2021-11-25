<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'team.create',
            'display_name' => 'Create Teams',
            'description' => 'create new teams',
        ]);

        // create an administrator role as standard
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Admin', // optional
            'description' => 'User is an administrator', // optional
        ]);

        $admin->attachPermissions(Permission::all());
    }
}
