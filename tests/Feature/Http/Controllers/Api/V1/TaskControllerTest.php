<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Usa o trait RefreshDatabase para garantir um banco limpo a cada teste
uses(RefreshDatabase::class);

// Cria um usuário antes de cada teste
beforeEach(function () {
    $this->user = User::factory()->create();
});

it('Listar Tarefas', function () {
    Task::factory()->count(3)->create();

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

    $this->assertDatabaseHas('tasks', ['title' => 'Nova tarefa']);
});

it('Validação dos campos obrigatórios ao criar uma tarefa', function () {
    $response = $this->actingAs($this->user, 'api')->postJson('/api/v1/tasks', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

it('Exibir os detalhes de uma tarefa', function () {
    $task = Task::factory()->create();

    $response = $this->actingAs($this->user, 'api')->getJson("/api/v1/tasks/{$task->id}");

    $response->assertStatus(200)
        ->assertJsonFragment(['title' => $task->title]);
});

it('Atualizar uma tarefa', function () {
    $task = Task::factory()->create(['is_completed' => false]);

    $data = ['title' => 'Título atualizado', 'is_completed' => true];

    $response = $this->actingAs($this->user, 'api')->putJson("/api/v1/tasks/{$task->id}", $data);

    $response->assertStatus(200)
        ->assertJsonFragment(['title' => 'Título atualizado', 'is_completed' => true]);

    $this->assertDatabaseHas('tasks', ['title' => 'Título atualizado', 'is_completed' => true]);
});

it('Validação dos campos ao atualizar uma tarefa', function () {
    $task = Task::factory()->create();

    $data = ['is_completed' => 'invalid_boolean'];

    $response = $this->actingAs($this->user, 'api')->putJson("/api/v1/tasks/{$task->id}", $data);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['is_completed']);
});

it('Deletar uma tarefa', function () {
    $task = Task::factory()->create();

    $response = $this->actingAs($this->user, 'api')->deleteJson("/api/v1/tasks/{$task->id}");

    $response->assertStatus(200)
        ->assertJsonFragment(['success' => true]);

    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});
