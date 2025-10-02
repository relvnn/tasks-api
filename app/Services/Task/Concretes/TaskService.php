<?php

namespace App\Services\Task\Concretes;

use App\Models\Task;
use App\Repositories\Task\Contracts\TaskRepositoryInterface;
use App\Services\Task\Contracts\TaskServiceInterface;
use Illuminate\Auth\Access\AuthorizationException;

class TaskService implements TaskServiceInterface
{
    protected TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Lista as tarefas paginadas.
     */
    public function listTasks(array $filters = [])
    {
        $query = $this->taskRepository->all();

        if (isset($filters['is_completed'])) {
            $query->where('is_completed', $filters['is_completed']);
        }

        if (isset($filters['due_date'])) {
            $query->whereDate('due_date', $filters['due_date']);
        }

        return $query->paginate(10);
    }


    /**
     * Cria uma nova tarefa.
     */
    public function createTask(array $data): Task
    {
        // Adiciona o user_id automaticamente
        $data['user_id'] = auth()->id();

        return $this->taskRepository->create($data);
    }

    /**
     * Atualiza uma tarefa existente, verificando o dono.
     */
    public function updateTask(Task $task, array $data, int $userId): Task
    {
        if ($task->user_id !== $userId) {
            throw new AuthorizationException('Você não tem permissão para executar esta ação.');
        }

        return $this->taskRepository->update($task, $data);
    }

    /**
     * Remove uma tarefa, verificando o dono.
     */
    public function deleteTask(Task $task, int $userId): bool
    {
        if ($task->user_id !== $userId) {
            throw new AuthorizationException('Você não tem permissão para executar esta ação.');
        }

        return $this->taskRepository->delete($task);
    }

    /**
     * Marca uma tarefa como concluída (ou desmarca), verificando o dono.
     */
    public function markAsDone(Task $task, int $userId): Task
    {
        if ($task->user_id !== $userId) {
            throw new AuthorizationException('Você não tem permissão para executar esta ação.');
        }

        // Alterna o valor de is_completed
        $task->is_completed = !$task->is_completed;

        return $this->taskRepository->update($task, ['is_completed' => $task->is_completed]);
    }
}
