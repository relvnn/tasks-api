<?php

namespace App\Http\Resources\Api\Task;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource para transformar o modelo Task em array para resposta JSON.
 */
class TaskResource extends JsonResource
{
    /**
     * Transforma a tarefa em um array para resposta da API.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'is_completed' => (bool) $this->is_completed,
            'due_date' => $this->due_date ? $this->due_date->toDateString() : null,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
