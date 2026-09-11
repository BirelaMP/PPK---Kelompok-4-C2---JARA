<?php

use App\Models\User;

test('administrators can access the user management index', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get('/admin/users');

    $response->assertStatus(200);
    $response->assertSee('User Management');
    $response->assertSee('Registered Accounts');
});

test('regular users cannot access admin user management', function () {
    $user = User::factory()->create(['role' => 'user']);

    $response = $this->actingAs($user)->get('/admin/users');

    $response->assertStatus(403);
});

test('administrators can create a new user account', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post('/admin/users', [
        'name' => 'Alice Admin Created',
        'email' => 'alice@jara.com',
        'password' => 'securepass123',
        'role' => 'user',
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', [
        'email' => 'alice@jara.com',
        'role' => 'user',
    ]);
});

test('administrators can update user details', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['name' => 'Old Name', 'role' => 'user']);

    $response = $this->actingAs($admin)->put("/admin/users/{$user->id}", [
        'name' => 'Updated Name',
        'email' => $user->email,
        'role' => 'admin',
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
        'role' => 'admin',
    ]);
});

test('administrators cannot delete their own account', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->delete("/admin/users/{$admin->id}");

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('users', [
        'id' => $admin->id,
    ]);
});
