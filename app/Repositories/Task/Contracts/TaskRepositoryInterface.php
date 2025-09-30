<?php

namespace App\Repositories\Task\Contracts;

use App\Models\Task;

interface TaskRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data): Task;
    public function update(Task $task, array $data): Task;
    public function delete(Task $task): bool;
}
