<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{

    /**
     * add new department
     * @param \Illuminate\Http\Request $req
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function new_department(Request $req)
    {
        try {
            //validate the requesr
            $validate = Validator::make($req->all(), [
                'name' => ['required'],
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }

            $user = $req->user();

            //add new department
            $department = Department::create([
                'name' => $req->name,
                'created_by' => $user->id,
            ]);
            return response()->json($department);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'an error has occured',
                'description' => 'an error has occured please try again'
            ], 500);
        }
    }


    /**
     * add users to departments
     * @param \Illuminate\Http\Request $req
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function add_user_to_department(Request $req)
    {
        try {

            //validate requesr
            $validate = Validator::make($req->all(), [
                'user_id' => ['required'],
                'department_id' => ['required'],
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }

            //data is valid

            //get department and user
            $department = Department::findOrFail($req->department_id);
            $user = User::findOrFail($req->user_id);

            $user->department_id = $department->id; //allocate user to the department
            $user->save(); //save user
            return response()->json($user);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'an error has occured',
                'description' => 'an error has occured please try again'
            ], 500);
        }
    }

    /**
     * find all departments
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function get_all_departments(Request $request)
    {
        try {
            $pageSize = $request->input('size', 10); // Default page size is 10
            $query = $request->input('query', ''); // Search query
            $page = $request->input('page', 1); //page 

            $department = Department::with('users')
                ->where('name', 'ilike',  "%$query%") //search insensitive
                ->orderBy('name', 'asc')
                ->paginate($pageSize, ["*"], "page", $page);
            return response()->json($department); //return response
        } catch (Exception $ex) {
            echo $ex;
            return response()->json([
                'error' => 'an error has occured',
                'description' => 'an error has occured please try again'
            ], 500);
        }
    }
}
