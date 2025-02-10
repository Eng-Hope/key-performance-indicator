<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post("/auth/register", [AuthController::class, "register"])
->middleware(["auth:api"]);

Route::post("/auth/login", action: [AuthController::class, "login"]);
Route::post("/auth/refresh", action: [AuthController::class, "refresh"]);
Route::post("/auth/logout", [AuthController::class, "logout"])
    ->middleware(["auth:api"]);

Route::get("/users", [UserController::class, "get_all_users"])
->middleware(["auth:api"]);
