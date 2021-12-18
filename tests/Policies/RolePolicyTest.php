<?php

use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);

    $this->user = User::factory()->create();

    actingAs($this->user);
});

it('has anyView function', function () {
    $this->assertFalse($this->user->can('viewAny', Role::class));

    $this->user->attachPermission('role.create');

    $this->assertTrue($this->user->can('viewAny', Role::class));

    $this->user->detachPermission('role.create');

    $this->assertFalse($this->user->can('viewAny', Role::class));

    $this->user->attachPermission('role.update');

    $this->assertTrue($this->user->can('viewAny', Role::class));
});

it('needs permission to create a new role', function () {
    $this->assertFalse($this->user->can('create', Role::class));

    $this->user->attachPermission('role.create');

    $this->assertTrue($this->user->can('create', Role::class));
});

it('needs permission to edit a role', function () {
    $role = Role::find(1);

    $this->assertFalse($this->user->can('update', $role));

    $this->user->attachPermission('role.update');

    $this->assertTrue($this->user->can('update', $role));
});
