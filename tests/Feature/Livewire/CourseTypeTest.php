<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);

    $this->user = User::factory()->create();

    actingAs($this->user);
});

it('has course type page which needs to be logged in', function () {
    $response = $this->get(route('coursetype'));

    $response->assertStatus(200);

    $response->assertSeeLivewire('course-type');

    auth()->logout($this->user);
    $response = $this->get(route('coursetype'));
    $response->assertRedirect(route('login'));
});

it('needs permission to create a new course type', function () {
    Livewire::test('course-type')
        ->call('create')
        ->assertForbidden();

    $this->user->attachPermission('courseType.create');

    Livewire::test('course-type')
        ->call('create')
        ->assertSuccessful();
});

it('needs permission to update a course type', function () {
    Livewire::test('course-type')
        ->call('edit')
        ->assertForbidden();

    $this->user->attachPermission('courseType.update');

    Livewire::test('course-type')
        ->call('edit')
        ->assertSuccessful();
});

it('needs permission to save a new course type or update', function () {
    Livewire::test('course-type')
        ->call('save')
        ->assertForbidden();

    $this->user->attachPermission('courseType.create');

    Livewire::test('course-type')
        ->call('save')
        ->assertSuccessful();

    $this->user->detachPermission('courseType.create');

    Livewire::test('course-type')
        ->call('save')
        ->assertForbidden();

    $this->user->attachPermission('courseType.update');

    Livewire::test('course-type')
        ->call('save')
        ->assertSuccessful();
});

it('has validations', function () {
    Livewire::test('course-type')
        ->set('editing.name', '')
        ->set('editing.category', '')
        ->set('editing.units', '')
        ->set('editing.units_per_day', '')
        ->set('editing.breaks', '')
        ->set('editing.seats', '')
        ->assertHasErrors([
            'editing.name',
            'editing.category',
            'editing.units',
            'editing.units_per_day',
            'editing.breaks',
            'editing.seats',
        ]);
});
