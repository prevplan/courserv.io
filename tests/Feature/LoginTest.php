<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

beforeEach(function (){
   $this->user = User::factory()->create();
});

it('has login page', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

it('shows a Livewire component at Login', function () {
    $this->get('/login')->assertSeeLivewire('auth.login');
});

it('needs a valid e-mail address to login', function () {
    Livewire::test('auth.login')
        ->set('email', 'test@example.com')
        ->assertHasNoErrors()
        ->set('email', '')
        ->assertHasErrors(['email' => 'required'])
        ->set('email', 'invalid')
        ->assertHasErrors(['email' => 'email']);
});

it('needs a password', function () {
    Livewire::test('auth.login')
        ->set('password', '')
        ->assertHasErrors(['password' => 'required']);
});

it('require a valid captcha to login', function () {
    Livewire::test('auth.login')
        ->set('email', 'test@example.com')
        ->set('password', 'wrong')
        ->call('login')
        ->assertHasErrors('captcha');
});

it('needs valid credentials', function () {
    Livewire::test('auth.login')
        ->set('email', 'test@example.com')
        ->set('password', 'wrong')
        ->emit('captchaSolved')
        ->call('login')
        ->assertHasErrors('email')
        ->assertSee(trans('auth.failed'));
});

it('get blocked after 3 failed login tries', function () {
    $i = 0;

    while ($i < 3) {
        Livewire::test('auth.login')
            ->set('email', 'test@example.com')
            ->set('password', 'wrong')
            ->emit('captchaSolved')
            ->call('login')
            ->assertHasNoErrors('captcha');
        $i++;
    }

    Livewire::test('auth.login')
        ->set('email', 'test@example.com')
        ->set('password', 'wrong')
        ->emit('captchaSolved')
        ->call('login')
        ->assertHasErrors('captcha')
        ->assertSee('Too many login attempts.');
});

it('can login', function () {
    $user = User::factory()->create(
        ['password' => Hash::make('password')]
    );

    Livewire::test('auth.login')
        ->set('email', $user->email)
        ->set('password', 'password')
        ->emit('captchaSolved')
        ->call('login')
        ->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);
});

it('can logout', function () {
   actingAs($this->user);

    $this->assertAuthenticated();

    Livewire::test('auth.logout')
        ->call('logout')
        ->assertRedirect('/');

    $this->assertGuest();
});
