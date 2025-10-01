<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class TaskService
{
    public function listTasks(array $filters): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Task::query();

        if (isset($filters['project_id'])) {
            $query->where('project_id', $filters['project_id']);
        }

        if (isset($filters['query'])) {
            $query->where('title', 'like', '%' . $filters['query'] . '%');
        }

        if (isset($filters['sort'])) {
            $query->orderBy($filters['order_by'], $filters['sort']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function listOwnerTasks(User $owner, array $filters): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Task::query()->where('owner_id', $owner->id);

        if (isset($filters['query'])) {
            $query->where('title', 'like', '%' . $filters['query'] . '%');
        }

        if (isset($filters['sort'])) {
            $query->orderBy($filters['order_by'], $filters['sort']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function createTask(User $creator, array $data): Task
    {
        $data['created_by'] = $creator->id;
        return Task::create($data);
    }

    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    public function deleteTask(Task $task): void
    {
        $task->delete();
    }
}


