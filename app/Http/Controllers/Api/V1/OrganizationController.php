<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\IndexOrganizationRequest;
use App\Http\Requests\Organization\UpdateOrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource with it's Users.
     */
    public function index(IndexOrganizationRequest $request)
    {
        $data = $request->validated();

        $users =  $request->user()->organization->run(function () use ($data) {
            $query = User::query();
            $query->when(isset($data['query']), function ($query) use ($data) {
                $query->where('name', 'like', '%' . $data['query'] . '%');
            });
            $query->when(isset($data['sort']), function ($query) use ($data) {
                $query->orderBy($data['order_by'], $data['sort']);
            });

            return $query->paginate($data['per_page'] ?? 15);
        });

        $organization = auth()->user()->organization;

        $organization->setRelation('users', $users);

        return $this->respondOk(OrganizationResource::make($organization) , "Organization Fetched successfully");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrganizationRequest $request)
    {
        $data = $request->validated();
        auth()->user()->organization()->update($data);
        return $this->respondOk(OrganizationResource::make(auth()->user()->organization) , "Organization updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
