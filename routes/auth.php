<?php

use App\Http\Controllers\API\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/login", [AuthController::class, "login"]);
        // Route::get("/provider/{platform}", [SocialiteController::class, "loginWithProvider"])->where('platform', 'facebook|google');
        // Route::get("/google-callback", [SocialiteController::class, "googleCallback"]);
        // Route::get("/facebook-callback", [SocialiteController::class, "facebookCallback"]);

});

Route::middleware('auth')->group(function () {
    Route::get("/logout", [AuthController::class, "logout"])->middleware("loggedIn");
    Route::get("/logout-all", [AuthController::class, "logoutAllDevices"])->middleware("loggedIn");
});
