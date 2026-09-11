<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('Sign In to Your Workspace');
});

test('register screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
    $response->assertSee('Create New Account');
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create([
        'email' => 'testuser@jara.com',
        'password' => Hash::make('secretpassword'),
    ]);

    $response = $this->post('/login', [
        'email' => 'testuser@jara.com',
        'password' => 'secretpassword',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('dashboard'));
});

test('users cannot authenticate with invalid password', function () {
    $user = User::factory()->create([
        'email' => 'invalidpass@jara.com',
        'password' => Hash::make('secretpassword'),
    ]);

    $response = $this->post('/login', [
        'email' => 'invalidpass@jara.com',
        'password' => 'wrongpassword',
    ]);

    $this->assertGuest();
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'John Developer',
        'email' => 'johndev@jara.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('users', [
        'email' => 'johndev@jara.com',
        'role' => 'user',
    ]);
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect(route('login'));
});
