<?php

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;

test('users can create tasks inside their task list', function () {
    $user = User::factory()->create();
    $list = TaskList::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post('/tasks', [
        'task_list_id' => $list->id,
        'title' => 'Write documentation',
        'description' => 'Document all API endpoints and models',
        'priority' => 'High',
        'status' => 'Pending',
        'deadline' => now()->addDays(5)->format('Y-m-d'),
        'assigned_to' => $user->id,
    ]);

    $response->assertRedirect(route('task-lists.show', $list));
    $this->assertDatabaseHas('tasks', [
        'task_list_id' => $list->id,
        'title' => 'Write documentation',
        'priority' => 'High',
        'status' => 'Pending',
    ]);
});

test('task validation enforces required title, priority, status, and deadline', function () {
    $user = User::factory()->create();
    $list = TaskList::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->post('/tasks', [
        'task_list_id' => $list->id,
        'title' => '',
        'priority' => 'InvalidPriority',
        'status' => 'InvalidStatus',
        'deadline' => 'not-a-date',
    ]);

    $response->assertSessionHasErrors(['title', 'priority', 'status', 'deadline']);
});

test('users can update task status', function () {
    $user = User::factory()->create();
    $list = TaskList::factory()->create(['user_id' => $user->id]);
    $task = Task::factory()->create([
        'task_list_id' => $list->id,
        'created_by' => $user->id,
        'status' => 'Pending',
    ]);

    $response = $this->actingAs($user)->patch("/tasks/{$task->id}/status", [
        'status' => 'Completed',
    ]);

    $response->assertSessionHas('success');
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'status' => 'Completed',
    ]);
});

test('users can delete tasks in their list', function () {
    $user = User::factory()->create();
    $list = TaskList::factory()->create(['user_id' => $user->id]);
    $task = Task::factory()->create([
        'task_list_id' => $list->id,
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)->delete("/tasks/{$task->id}");

    $response->assertRedirect(route('task-lists.show', $list));
    $this->assertDatabaseMissing('tasks', [
        'id' => $task->id,
    ]);
});
