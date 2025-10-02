<?php

namespace App\Services\Task\Contracts;

use App\Models\Task;

interface TaskServiceInterface
{
    /**
     * Lista todas as tarefas.
     */
    public function listTasks(array $filters = []);

    /**
     * Cria uma nova tarefa com os dados informados.
     */
    public function createTask(array $data): Task;

    /**
     * Atualiza uma tarefa existente com os dados informados.
     */
    public function updateTask(Task $task, array $data, int $userId): Task;

    /**
     * Remove uma tarefa do banco de dados.
     */
    public function deleteTask(Task $task, int $userId): bool;

    /**
     * Marca uma tarefa como concluída.
     */
    public function markAsDone(Task $task, int $userId): Task;
}
