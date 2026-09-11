<?php

use App\Models\TaskList;
use App\Models\User;

test('users can view their task lists', function () {
    $user = User::factory()->create();
    $list = TaskList::factory()->create([
        'user_id' => $user->id,
        'name' => 'Design Sprint List',
    ]);

    $response = $this->actingAs($user)->get('/task-lists');

    $response->assertStatus(200);
    $response->assertSee('Design Sprint List');
});

test('users can create a new task list', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/task-lists', [
        'name' => 'Backend Refactor',
        'description' => 'Clean up services and repositories',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('task_lists', [
        'name' => 'Backend Refactor',
        'user_id' => $user->id,
    ]);
});

test('owners can invite collaborators to their task list', function () {
    $owner = User::factory()->create();
    $colleague = User::factory()->create();
    $list = TaskList::factory()->create(['user_id' => $owner->id]);

    $response = $this->actingAs($owner)->post("/task-lists/{$list->id}/members", [
        'user_id' => $colleague->id,
    ]);

    $response->assertSessionHas('success');
    $this->assertTrue($list->members()->where('users.id', $colleague->id)->exists());
});

test('invited collaborators can view shared task lists', function () {
    $owner = User::factory()->create();
    $colleague = User::factory()->create();
    $list = TaskList::factory()->create(['user_id' => $owner->id, 'name' => 'Shared Team Project']);
    $list->members()->attach($colleague->id);

    $response = $this->actingAs($colleague)->get("/task-lists/{$list->id}");

    $response->assertStatus(200);
    $response->assertSee('Shared Team Project');
});

test('unauthorized users cannot access a private task list', function () {
    $owner = User::factory()->create();
    $stranger = User::factory()->create(['role' => 'user']);
    $list = TaskList::factory()->create(['user_id' => $owner->id]);

    $response = $this->actingAs($stranger)->get("/task-lists/{$list->id}");

    $response->assertStatus(403);
});

test('owners can remove a member from their task list', function () {
    $owner = User::factory()->create();
    $colleague = User::factory()->create();
    $list = TaskList::factory()->create(['user_id' => $owner->id]);
    $list->members()->attach($colleague->id);

    $response = $this->actingAs($owner)->delete("/task-lists/{$list->id}/members/{$colleague->id}");

    $response->assertSessionHas('success');
    $this->assertFalse($list->members()->where('users.id', $colleague->id)->exists());
});

test('owners can access the dedicated member management page', function () {
    $owner = User::factory()->create();
    $list = TaskList::factory()->create(['user_id' => $owner->id, 'name' => 'Architecture Team']);

    $response = $this->actingAs($owner)->get("/task-lists/{$list->id}/members");

    $response->assertStatus(200);
    $response->assertSee('Team Collaboration');
    $response->assertSee('Invite New Member');
});
