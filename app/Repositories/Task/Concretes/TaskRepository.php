<?php

namespace App\Repositories\Task\Concretes;

use App\Models\Task;
use App\Repositories\Task\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * Retorna um query builder para a model Task.
     */
    public function all()
    {
        return Task::query();
    }

    /**
     * Busca uma tarefa pelo ID. Lança exceção se não encontrar.
     */
    public function find($id)
    {
        return Task::findOrFail($id);
    }

    /**
     * Cria uma nova tarefa com os dados informados.
     */
    public function create(array $data): Task
    {
        return Task::create($data);
    }

    /**
     * Atualiza uma tarefa existente com os dados informados.
     */
    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    /**
     * Remove uma tarefa do banco de dados.
     */
    public function delete(Task $task): bool
    {
        return $task->delete();
    }
}
