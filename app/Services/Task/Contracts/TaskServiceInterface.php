<?php

namespace App\Services\Task\Contracts;

use App\Models\Task;

interface TaskServiceInterface
{
    /**
     * Lista todas as tarefas.
     */
    public function listTasks();
    /**
     * Cria uma nova tarefa com os dados informados.
     */
    public function createTask(array $data): Task;
    /**
     * Atualiza uma tarefa existente com os dados informados.
     */
    public function updateTask(Task $task, array $data): Task;
    /**
     * Remove uma tarefa do banco de dados.
     */
    public function deleteTask(Task $task): bool;
}
