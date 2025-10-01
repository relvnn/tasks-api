<?php

namespace App\Services\Task\Concretes;

use App\Models\Task;
use App\Repositories\Task\Contracts\TaskRepositoryInterface;
use App\Services\Task\Contracts\TaskServiceInterface;

class TaskService implements TaskServiceInterface
{
    protected TaskRepositoryInterface $taskRepository;

    /**
     * Construtor da classe. Injeta o repositório de tarefas.
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Lista as tarefas paginadas.
     */
    public function listTasks()
    {
        // Retorna query para permitir filtros/paginação
        return $this->taskRepository->all()->paginate(10);
    }

    /**
     * Cria uma nova tarefa com os dados informados.
     */
    public function createTask(array $data): Task
    {
        return $this->taskRepository->create($data);
    }

    /**
     * Atualiza uma tarefa existente com os dados informados.
     */
    public function updateTask(Task $task, array $data): Task
    {
        return $this->taskRepository->update($task, $data);
    }

    /**
     * Remove uma tarefa do banco de dados.
     */
    public function deleteTask(Task $task): bool
    {
        return $this->taskRepository->delete($task);
    }
}
