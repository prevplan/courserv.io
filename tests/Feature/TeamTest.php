<?php

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);

    $this->team = Team::create([
        'name' => 'example-team',
        'display_name' => 'example team',
    ]);

    $this->teams = $this->team;

    $this->user = User::factory()->create();
    $this->user->teams()->attach($this->team);
    $this->user->attachRole('admin');

    actingAs($this->user);
});

it('has team page', function () {
    $response = $this->get('/teams');

    $response->assertStatus(200);

    $response->assertSeeLivewire('team');
});

it('shows team menu only to authorized users', function () {
    $this->get('/home')->assertSee('Teams');

    $this->user->detachRole('admin');
    $this->get('/home')->assertDontSee('Teams');

    $this->user->attachRole('admin', $this->team);
    $this->get('/home')->assertSee('Teams');
});

it('needs authorization to create a team', function () {
    $this->role = Role::find(1);
    $this->role->detachPermission('team.create');

    Livewire::test('team')
        ->call('create')
        ->assertForbidden();
});

it('needs authorization to edit a team', function () {
    $this->role = Role::find(1);
    $this->role->detachPermission('team.update');

    Livewire::test('team')
        ->call('edit')
        ->assertForbidden();
});

it('has validations', function () {
    Livewire::test('team')
        ->set('editing.name', '')
        ->set('editing.display_name', '')
        ->assertHasErrors([
            'editing.name',
            'editing.display_name',
        ]);
});

it('needs a unique team name', function () {
    Livewire::test('team')
        ->set('editing.name', 'example-team')
        ->set('editing.display_name', 'example team')
        ->assertHasErrors([
            'editing.name',
            'editing.display_name',
        ]);
});
