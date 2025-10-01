<?php

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\InvitationController;
use App\Http\Controllers\API\V1\OrganizationController;
use App\Http\Controllers\API\V1\TenantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});


Route::group([
    'prefix' => 'v1'
], function ($router) {

    Route::group([
        'prefix' => 'auth'
    ], function ($router) {
        require base_path('routes/auth.php');
    });

    Route::group([
        'middleware' => 'auth:sanctum'
    ], function ($router) {
        Route::apiResource('organization', OrganizationController::class);
        Route::apiResource('invitation', InvitationController::class);
        Route::post('invitation/useInvitation', [InvitationController::class, 'useInvitation']);

    });


});

