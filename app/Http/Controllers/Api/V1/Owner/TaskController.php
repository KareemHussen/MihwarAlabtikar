<?php

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\IndexOwnerTaskRequest;
use App\Http\Requests\Task\IndexTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(IndexTaskRequest $request)
    {
        $data = $request->validated();
        $tasks = $this->taskService->listTasks($data);
        return $this->respondOk(TaskResource::collection($tasks) , "Tasks Fetched successfully");
    }

        /**
     * Display a listing of the resource.
     */
    public function index_owner(IndexOwnerTaskRequest $request)
    {
        $data = $request->validated();
        $tasks = $this->taskService->listOwnerTasks(auth()->user(), $data);
        return $this->respondOk(TaskResource::collection($tasks) , "Tasks Fetched successfully");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $data = $request->validated();
        $task = $this->taskService->createTask(auth()->user(), $data);
        return $this->respondCreated(TaskResource::make($task) , "Task created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return $this->respondOk(TaskResource::make($task) , "Task Fetched successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $data = $request->validated();
        $task = $this->taskService->updateTask($task, $data);
        return $this->respondOk(TaskResource::make($task) , "Task updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return $this->respondNoContent();
    }
}
