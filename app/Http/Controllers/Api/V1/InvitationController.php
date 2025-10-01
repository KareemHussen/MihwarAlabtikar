<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invitation\StoreInvitationRequest;
use App\Http\Requests\Invitation\UseInvitationRequest;
use App\Models\Invitation;
use App\Http\Resources\InvitationResource;
use App\Models\User;
use Illuminate\Http\Request;
use Stancl\Tenancy\Tenancy;

class InvitationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invitations = Invitation::paginate();
        return $this->respondOk(InvitationResource::collection($invitations) , "Invitations Fetched successfully");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvitationRequest $request)
    {
        $data = $request->validated();
        $token = uuid_create();
        $data['token'] = $token;
        $invitation = Invitation::create($data + ["organization_id" => auth()->user()->organization->id]);

        //Create Event To Send Invitation
        // event(new \App\Events\InvitationEvent($invitation));

        return $this->respondCreated(InvitationResource::make($invitation) , "Invitation sent successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Invitation $invitation)
    {
        return $this->respondOk(InvitationResource::make($invitation) , "Invitation Fetched successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invitation $invitation)
    {
        $invitation->delete();
        return $this->respondNoContent();
    }

    public function useInvitation(UseInvitationRequest $request)
    {
        $data = $request->validated();
        $invitation = Invitation::where('token' , $data['token'])->first();

        if ($invitation->email != auth()->user()->email) {
            return $this->respondError('You are not allowed to use this invitation');
        }

        $invitation->update(['accepted_at' => now()]);


        $invitation->organization->run(function () use ($invitation) {
            $user = User::create(auth()->user()->toArray() + ["password" => auth()->user()->password]);
            $user->syncRoles($invitation->role);
        });
        

        return $this->respondNoContent();
    }
}
