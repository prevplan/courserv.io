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
            'name' => 'course.create',
            'display_name' => 'Create Course',
            'description' => 'create a new course',
            'group' => 'course',
        ]);

        Permission::create([
            'name' => 'course.update',
            'display_name' => 'Edit Course',
            'description' => 'create edit a course',
            'group' => 'course',
        ]);

        Permission::create([
            'name' => 'course.view',
            'display_name' => 'View Course',
            'description' => 'see all courses',
            'group' => 'course',
        ]);

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
            'name' => 'participant.create',
            'display_name' => 'Create Participant',
            'description' => 'create a new participant',
            'group' => 'participants',
        ]);

        Permission::create([
            'name' => 'participant.view',
            'display_name' => 'View Participant',
            'description' => 'can see the participant',
            'group' => 'participants',
        ]);

        Permission::create([
            'name' => 'participant.update',
            'display_name' => 'Edit Participant',
            'description' => 'can edit a participant',
            'group' => 'participants',
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
            'name' => 'price.create',
            'display_name' => 'Create price',
            'description' => 'can create a new price',
            'group' => 'price',
        ]);

        Permission::create([
            'name' => 'price.update',
            'display_name' => 'Update price',
            'description' => 'can update a price',
            'group' => 'price',
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
