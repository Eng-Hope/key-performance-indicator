<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;



Route::post("/auth/login", action: [AuthController::class, "login"]);
Route::post("/auth/refresh", action: [AuthController::class, "refresh"]);

Route::middleware(["auth:api"])->group(function () {

    Route::post("/auth/logout", [AuthController::class, "logout"]);
    Route::post("/auth/register", [AuthController::class, "register"])
    ->middleware(AdminMiddleware::class);

    Route::get("/users", [UserController::class, "get_all_users"])
    ->middleware(AdminMiddleware::class);
    Route::get("/dashboard", [UserController::class,"dashboard"]);

    Route::post("/department", [DepartmentController::class, "new_department"])
    ->middleware(AdminMiddleware::class);;
    Route::get("/department", [DepartmentController::class, "get_all_departments"])
    ->middleware(AdminMiddleware::class);;
    Route::post("/department/add-users", [DepartmentController::class,"add_user_to_department"])
    ->middleware(AdminMiddleware::class);;


    Route::post("/project", [ProjectController::class, "new_project"])
    ->middleware(AdminMiddleware::class);;
    Route::get("/project", [ProjectController::class, "get_all_projects"])
    ->middleware(AdminMiddleware::class);;
    Route::post("/project/add-users", [ProjectController::class,"add_user_to_project"])
    ->middleware(AdminMiddleware::class);;
    


    Route::post("/kpi", [KpiController::class, "new_kpi"])
    ->middleware(AdminMiddleware::class);;
    Route::get("/kpi", [KpiController::class, "get_all_kpis"])
    ->middleware(AdminMiddleware::class);;
    Route::put("/kpi", [KpiController::class, "edit_user_kpi"])
    ->middleware(AdminMiddleware::class);;
    Route::post("/kpi/add-users", [KpiController::class,"add_user_to_kpi"])
    ->middleware(AdminMiddleware::class);;
    Route::get("/kpi/performance", [KpiController::class,"get_user_performance"]);

});




