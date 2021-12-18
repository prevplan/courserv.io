<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);

//    $this->team = Team::create([
//        'name' => 'example-team',
//        'display_name' => 'example team',
//    ]);
//
//    $this->teams = $this->team;

    $this->user = User::factory()->create();
//    $this->user->teams()->attach($this->team);
//    $this->user->attachRole('admin');

    actingAs($this->user);
});

it('has course type page which needs to be logged in and authorized', function () {
    $response = $this->get(route('roles'));
    $response->assertForbidden();

    $this->user->attachPermission('role.create');

    $response = $this->get(route('roles'));
    $response->assertStatus(200);
    $response->assertSeeLivewire('role');

    $this->user->detachPermission('role.create');
    $this->user->attachPermission('role.update');

    $response = $this->get(route('roles'));
    $response->assertStatus(200);
    $response->assertSeeLivewire('role');

    auth()->logout($this->user);
    $response = $this->get(route('roles'));
    $response->assertRedirect(route('login'));
});

it('shows role menu only to authorized users', function () {
    $this->get(route('home'))->assertDontSee(_i('Roles'));

    $this->user->attachPermission('role.create');

    $this->get(route('home'))->assertSee(_i('Roles'));
});

it('needs authorization to create a role', function () {
    $this->user->attachPermission('role.create');

    Livewire::test('role')
        ->call('create')
        ->assertSuccessful();

    $this->user->detachPermission('role.create');
    $this->user->attachPermission('role.update'); // 'placeholder' to avoid fingerprint error

    Livewire::test('role')
        ->call('create')
        ->assertForbidden();
});

it('needs authorization to edit a role', function () {
    $this->user->attachPermission('role.update');

    Livewire::test('role')
        ->call('edit')
        ->assertSuccessful();

    $this->user->detachPermission('role.update');
    $this->user->attachPermission('role.create'); // 'placeholder' to avoid fingerprint error

    Livewire::test('role')
        ->call('edit')
        ->assertForbidden();
});
