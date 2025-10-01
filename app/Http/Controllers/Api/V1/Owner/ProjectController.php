<?php

namespace App\Http\Controllers\API\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\AddUserProjectRequest;
use App\Http\Requests\Project\IndexProjectRequest;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $projectService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(IndexProjectRequest $request)
    {
        $data = $request->validated();
        $projects = $this->projectService->listUserProjects(auth()->user(), $data);
        return $this->respondOk(ProjectResource::collection($projects) , "Projects Fetched successfully");
    }

    /**
     * Display a listing of the resource.
     */
    public function index_owner(IndexProjectRequest $request)
    {
        $data = $request->validated();
        $projects = $this->projectService->listOwnerProjects(auth()->user(), $data);
        return $this->respondOk(ProjectResource::collection($projects) , "Projects Fetched successfully");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        $project = $this->projectService->createProject(auth()->user(), $data);
        return $this->respondCreated(ProjectResource::make($project) , "Project created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $users = $project->users()->paginate();
        return $this->respondOk(ProjectResource::make($project->setRelation('users', $users)) , "Project Fetched successfully with users");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $data = $request->validated();
        $project = $this->projectService->updateProject($project, $data);
        return $this->respondOk(ProjectResource::make($project) , "Project updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->projectService->deleteProject($project);
        return $this->respondNoContent();
    }

    public function addUser(Project $project , AddUserProjectRequest $request)
    {
        $this->projectService->addUserToProject($project, $request->validated()['user_id']);
        return $this->respondNoContent();
    }
}
