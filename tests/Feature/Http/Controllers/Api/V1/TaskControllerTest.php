<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->otherUser = User::factory()->create();
});

it('Listar Tarefas', function () {
    Task::factory()->count(3)->create([
        'user_id' => $this->user->id
    ]);

    $response = $this->actingAs($this->user, 'api')->getJson('/api/v1/tasks');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'description', 'is_completed', 'due_date', 'created_at', 'updated_at']
            ]
        ]);
});


it('Criar Tarefas', function () {
    $data = [
        'title' => 'Nova tarefa',
        'description' => 'Descrição da tarefa',
        'due_date' => now()->addDay()->toDateString(),
    ];

    $response = $this->actingAs($this->user, 'api')->postJson('/api/v1/tasks', $data);

    $response->assertStatus(201)
        ->assertJsonFragment(['title' => 'Nova tarefa']);

    $this->assertDatabaseHas('tasks', ['title' => 'Nova tarefa', 'user_id' => $this->user->id]);
});

it('Validação dos campos obrigatórios ao criar uma tarefa', function () {
    $response = $this->actingAs($this->user, 'api')->postJson('/api/v1/tasks', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

it('Exibir os detalhes de uma tarefa', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user, 'api')->getJson("/api/v1/tasks/{$task->id}");

    $response->assertStatus(200)
        ->assertJsonFragment(['title' => $task->title]);
});

it('Atualizar uma tarefa', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id, 'is_completed' => false]);

    $data = ['title' => 'Título atualizado', 'is_completed' => true];

    $response = $this->actingAs($this->user, 'api')->putJson("/api/v1/tasks/{$task->id}", $data);

    $response->assertStatus(200)
        ->assertJsonFragment(['title' => 'Título atualizado', 'is_completed' => true]);

    $this->assertDatabaseHas('tasks', ['title' => 'Título atualizado', 'is_completed' => true]);
});

it('Validação dos campos ao atualizar uma tarefa', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id]);

    $data = ['is_completed' => 'invalid_boolean'];

    $response = $this->actingAs($this->user, 'api')->putJson("/api/v1/tasks/{$task->id}", $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['is_completed']);
});

it('Deletar uma tarefa', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->user, 'api')->deleteJson("/api/v1/tasks/{$task->id}");

    $response->assertStatus(200)
        ->assertJsonFragment(['success' => true]);

    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

it('Marca uma tarefa como concluída', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id, 'is_completed' => false]);

    $response = $this->actingAs($this->user, 'api')
        ->patchJson("/api/v1/tasks/{$task->id}/done");

    $response->assertStatus(200)
        ->assertJsonFragment(['is_completed' => true]);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'is_completed' => true,
    ]);
});

it('Não permite que outro usuário edite a tarefa', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->otherUser, 'api')
        ->putJson("/api/v1/tasks/{$task->id}", ['title' => 'Novo título']);

    $response->assertStatus(403);
});

it('Não permite que outro usuário delete a tarefa', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id]);

    $response = $this->actingAs($this->otherUser, 'api')
        ->deleteJson("/api/v1/tasks/{$task->id}");

    $response->assertStatus(403);
});

it('Não permite que outro usuário marque a tarefa como concluída', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id, 'is_completed' => false]);

    $response = $this->actingAs($this->otherUser, 'api')
        ->patchJson("/api/v1/tasks/{$task->id}/done");

    $response->assertStatus(403);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'is_completed' => false,
    ]);
});
