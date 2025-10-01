<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Task\StoreTaskRequest;
use App\Http\Requests\Api\V1\Task\UpdateTaskRequest;
use App\Http\Resources\Api\Task\TaskResource;
use App\Services\Task\Contracts\TaskServiceInterface;
use App\Models\Task;
use OpenApi\Annotations as OA;

/**
 * Controller responsável por gerenciar as tarefas via API.
 */
class TaskController extends Controller
{
    protected TaskServiceInterface $taskService;

    /**
     * Injeta o serviço de tarefas.
     */
    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Lista as tarefas paginadas.
     */
    public function index()
    {
        $tasks = $this->taskService->listTasks();
        return TaskResource::collection($tasks);
    }

    /**
     * Cria uma nova tarefa.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->createTask($request->validated());
        return new TaskResource($task);
    }

    /**
     * Exibe os detalhes de uma tarefa.
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Atualiza uma tarefa existente.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task = $this->taskService->updateTask($task, $request->validated());
        return new TaskResource($task);
    }

    /**
     * Remove uma tarefa do sistema.
     */
    public function destroy(Task $task)
    {
        $this->taskService->deleteTask($task);
        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }
}
