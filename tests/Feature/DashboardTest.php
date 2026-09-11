<?php

use App\Models\User;

test('guests are redirected to login when visiting dashboard', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect(route('login'));
});

test('authenticated users see the task overview dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Overview Dashboard');
    $response->assertSee('Upcoming Deadlines');
    $response->assertSee('Overall Task Completion Progress');
    $response->assertSee('Tasks by Priority');
});
