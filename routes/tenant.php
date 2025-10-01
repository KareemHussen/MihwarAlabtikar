<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\Owner\ProjectController;
use App\Http\Controllers\API\V1\Owner\TaskController;
use App\Http\Controllers\App\ProfileController;
use App\Http\Controllers\App\UserController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    

    Route::group([
        'prefix' => 'api/v1/auth'
    ], function ($router) {
        
        Route::middleware('guest')->group(function () {
            Route::post("/signup", [AuthController::class, "register"]);
                Route::post("/signin", [AuthController::class, "login"]);
                // Route::get("/provider/{platform}", [SocialiteController::class, "loginWithProvider"])->where('platform', 'facebook|google');
                // Route::get("/google-callback", [SocialiteController::class, "googleCallback"]);
                // Route::get("/facebook-callback", [SocialiteController::class, "facebookCallback"]);
        
        });
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::get("/signout", [AuthController::class, "logout"]);
            Route::get("/signout-all", [AuthController::class, "logoutAllDevices"]);
        });
        
    });





    Route::middleware('auth:sanctum')->prefix('api/v1')->group(function () {
        // Admin: create/edit/delete & custom routes FIRST
        Route::middleware(['role:Admin'])->group(function () {
            Route::get('project/index_owner', [ProjectController::class, 'index_owner']);
            Route::post('project/add_user', [ProjectController::class, 'addUser']);
            Route::get('task/index_owner', [TaskController::class, 'index_owner']);

            // Write operations
            Route::apiResource('project', ProjectController::class)->only(['store', 'update', 'destroy']);
            Route::apiResource('task', TaskController::class)->only(['store', 'update', 'destroy']);
        });

        // Viewer/Admin: read-only
        Route::middleware(['role:Viewer|Admin'])->group(function () {
            Route::apiResource('project', ProjectController::class)->only(['index', 'show']);
            Route::apiResource('task', TaskController::class)->only(['index', 'show']);
        });
    });
    


});
