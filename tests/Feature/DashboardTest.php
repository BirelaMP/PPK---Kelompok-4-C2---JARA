<?php

use App\Models\User;

test('guests are redirected to login when visiting dashboard', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect(route('login'));
});

test('regular users see the user dashboard', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Overview Dashboard');
    $response->assertSee('Upcoming Deadlines');
    $response->assertSee('Overall Task Completion Progress');
});

test('administrators see the admin dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Administrator System Overview');
    $response->assertSee('Total Registered Users');
    $response->assertSee('Tasks by Priority');
});
