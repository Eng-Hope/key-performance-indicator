<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Kpi;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

  /**
   * get all users
   * @param \Illuminate\Http\Request $request
   * @return JsonResponse|mixed
   */
  public function get_all_users(Request $request): JsonResponse{
    $pageSize = $request->input('size', 10); // Default page size is 10
    $query = $request->input('query', ''); // Search query
    $page = $request->input('page', 1);//page 

    return response()
    ->json(User::where('name', 'ilike',  "%$query%")
    ->orderBy('name', 'asc')
    ->paginate($pageSize, ["*"],"page", $page));
  }


  public function dashboard(): JsonResponse{
    $user_count = User::all()->count();
    $project_count = Project::all()->count();
    $department_count = Department::all()->count();
    $kpi_count = Kpi::all()->count();

    return response()->json([
      "user_count"=> $user_count,
      'project_count'=> $project_count,
      'department_counr'=> $department_count,
      'kpi_count' => $kpi_count
    ]);
  }

}
