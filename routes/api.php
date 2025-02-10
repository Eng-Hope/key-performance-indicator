<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



Route::post("/auth/login", action: [AuthController::class, "login"]);
Route::post("/auth/refresh", action: [AuthController::class, "refresh"]);

Route::middleware(["auth:api"])->group(function () {

    Route::post("/auth/logout", [AuthController::class, "logout"]);
    Route::post("/auth/register", [AuthController::class, "register"]);

    Route::get("/users", [UserController::class, "get_all_users"]);

    Route::post("/department", [DepartmentController::class, "new_department"]);
    Route::get("/department", [DepartmentController::class, "get_all_departments"]);
    Route::post("/department/add-users", [DepartmentController::class,"add_user_to_department"]);


    Route::post("/project", [ProjectController::class, "new_project"]);
    Route::get("/project", [ProjectController::class, "get_all_projects"]);
    Route::post("/project/add-users", [ProjectController::class,"add_user_to_project"]);
    


    Route::post("/kpi", [KpiController::class, "new_kpi"]);
    Route::get("/kpi", [KpiController::class, "get_all_kpis"]);
    Route::put("/kpi", [KpiController::class, "edit_user_kpi"]);
    Route::post("/kpi/add-users", [KpiController::class,"add_user_to_kpi"]);
    Route::get("/kpi/performance", [KpiController::class,"get_user_performance"]);

});



