<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $tenant = Organization::create($data + [
            'owner_id' => $user->id,
        ]);

        $tenant->domains()->create([
            'domain' => $data['domain_name'] . '.' . config('app.domain'),
        ]);

        $user->syncRoles('Owner');

        return $user;
    }

    public function login(array $data): User
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            abort(401, 'Bad credentials.');
        }

        $token = $user->createToken(env('SANCTUM_TOKEN'))->plainTextToken;
        $user->token = $token;
        $user->load('organization');

        return $user;
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function logoutAllDevices(User $user): void
    {
        $user->tokens()->delete();
    }
}


