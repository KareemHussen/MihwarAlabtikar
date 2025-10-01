<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\UseInvitationRequest;
use App\Http\Resources\InvitationResource;
use App\Http\Resources\UserResource;
use App\Models\Invitation;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Stancl\Tenancy\Facades\Tenancy;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function register(RegisterUserRequest $request)
    {
        $user = $this->authService->register($request->validated());
        return $this->respondCreated(UserResource::make($user), 'Registered successfully');
    }

    public function login(LoginUserRequest $request)
    {
        $user = $this->authService->login($request->validated());
        return $this->respondOk(UserResource::make($user) , 'Login successfully');
    }

    public function logout(Request $request){
        $this->authService->logout($request->user());
        return $this->respondNoContent();
    }

    public function logoutAllDevices(Request $request){
        $this->authService->logoutAllDevices($request->user());
        return $this->respondNoContent();
    }

    public function user(Request $request){
        
        return $this->respondOk(UserResource::make(auth()->user()));
    }

}
    
