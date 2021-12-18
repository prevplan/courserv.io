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
            'group' => 'team',
        ]);

        Permission::create([
            'name' => 'team.view',
            'display_name' => 'View Teams',
            'description' => 'can see the teams',
            'group' => 'team',
        ]);

        Permission::create([
            'name' => 'team.update',
            'display_name' => 'Edit Teams',
            'description' => 'can edit a team',
            'group' => 'team',
        ]);

        Permission::create([
            'name' => 'user.create',
            'display_name' => 'Create User',
            'description' => 'create a new user',
            'group' => 'user',
        ]);

        Permission::create([
            'name' => 'user.view',
            'display_name' => 'View User',
            'description' => 'can see the user',
            'group' => 'user',
        ]);

        Permission::create([
            'name' => 'user.update',
            'display_name' => 'Edit User',
            'description' => 'can edit a user',
            'group' => 'user',
        ]);

        Permission::create([
            'name' => 'courseType.create',
            'display_name' => 'Create Course Type',
            'description' => 'create a new course type',
            'group' => 'courseType',
        ]);

        Permission::create([
            'name' => 'courseType.update',
            'display_name' => 'Update Course Type',
            'description' => 'can update a course type',
            'group' => 'courseType',
        ]);

        Permission::create([
            'name' => 'role.create',
            'display_name' => 'Create Roles',
            'description' => 'can create a new role',
            'group' => 'role',
        ]);

        Permission::create([
            'name' => 'role.update',
            'display_name' => 'Update Roles',
            'description' => 'can update a role',
            'group' => 'role',
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
