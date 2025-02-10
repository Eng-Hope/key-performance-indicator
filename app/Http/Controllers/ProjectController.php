<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * add new project
     * @param \Illuminate\Http\Request $req
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function new_project(Request $req)
    {
        try {
            //validate the request
            $validate = Validator::make($req->all(), [
                'name' => ['required'],
                'start_date'=>['date', 'required']
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }

            $user = $req->user();

            //add new project
            $project =   Project::create([
                'name' => $req->name,
                'created_by' => $user->id,
                'start_date'=> $req->start_date,
                'description'=> $req->description,
                'end_date' => $req->end_date,
            ]);
            return response()->json($project);
        } catch (Exception $ex) {
            echo $ex;
            return response()->json([
                'error' => 'an error has occured',
                'description' => 'an error has occured please try again'
            ], 500);
        }
    }


    /**
     * add users to project
     * @param \Illuminate\Http\Request $req
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function add_user_to_project(Request $req)
    {
        try {

            //validate request
            $validate = Validator::make($req->all(), [
                'user_id' => ['required'],
                'project_id' => ['required'],
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }

            //data is valid

            //get project and user
            $project = Project::findOrFail($req->project_id);
            $user = User::findOrFail($req->user_id);

            //attach user to project
            $user->projects()->attach($project->id);
            //return response
            return response()->json(["user"=> $user, "project"=> $project]);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'an error has occured',
                'description' => 'an error has occured please try again'
            ], 500);
        }
    }

    /**
     * find all projects
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function get_all_projects(Request $request)
    {
        try {
            $pageSize = $request->input('size', 10); // Default page size is 10
            $query = $request->input('query', ''); // Search query
            $page = $request->input('page', 1); //page 

            $project = Project::with('users')
                ->where('name', 'ilike',  "%$query%") //search insensitive
                ->orderBy('name', 'asc')
                ->paginate($pageSize, ["*"], "page", $page);
            return response()->json($project); //return response
        } catch (Exception $ex) {
            echo $ex;
            return response()->json([
                'error' => 'an error has occured',
                'description' => 'an error has occured please try again'
            ], 500);
        }
    }
}
