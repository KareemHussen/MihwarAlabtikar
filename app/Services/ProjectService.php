<?php

namespace App\Services;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;

class ProjectService
{
    public function listUserProjects(User $user, array $filters): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = $user->projects()->getQuery();

        if (isset($filters['query'])) {
            $query->where('name', 'like', '%' . $filters['query'] . '%');
        }

        if (isset($filters['sort'])) {
            $query->orderBy($filters['order_by'], $filters['sort']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function listOwnerProjects(User $user, array $filters): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Project::query()->where('owner_id', $user->id);

        if (isset($filters['query'])) {
            $query->where('name', 'like', '%' . $filters['query'] . '%');
        }

        if (isset($filters['sort'])) {
            $query->orderBy($filters['order_by'], $filters['sort']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function createProject(User $owner, array $data): Project
    {
        $data['owner_id'] = $owner->id;
        return Project::create($data);
    }

    public function updateProject(Project $project, array $data): Project
    {
        $project->update($data);
        return $project;
    }

    public function deleteProject(Project $project): void
    {
        $project->delete();
    }

    public function addUserToProject(Project $project, int $userId): void
    {
        $project->users()->attach($userId);
    }
}


