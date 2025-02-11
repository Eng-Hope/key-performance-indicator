<?php

namespace App\Http\Controllers;

use App\Models\Kpi;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KpiController extends Controller
{

    /**
     * add new kpi
     * @param \Illuminate\Http\Request $req
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function new_kpi(Request $req)
    {
        try {
            //validate the request
            $validate = Validator::make($req->all(), [
                'name' => ['required'],
                'measurement' => ['required'],
                'review_duration' => ['required'],
                'target' => ['required'],
                'weight' => ['required'],
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }

            $user = $req->user();

            //add new kpi
            $kpi =   Kpi::create([
                'name' => $req->name,
                'measurement' => $req->measurement,
                'review_duration' => $req->review_duration,
                'target' => $req->target,
                'weight' => $req->weight,
                'created_by'=> $user->id,
            ]);
            return response()->json($kpi);
        } catch (Exception $ex) {
            echo $ex;
            return response()->json([
                'error' => 'an error has occured',
                'description' => 'an error has occured please try again'
            ], 500);
        }
    }



    /**
     * add users to kpi
     * @param \Illuminate\Http\Request $req
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function add_user_to_kpi(Request $req)
    {
        try {

            //validate request
            $validate = Validator::make($req->all(), [
                'user_id' => ['required'],
                'kpi_id' => ['required'],
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }

            //data is valid

            //get kpi and user
            $kpi = Kpi::findOrFail($req->kpi_id);
            $user = User::findOrFail($req->user_id);

            //attach user to kpi
            $user->kpis()->attach($kpi->id);
            //return response
            return response()->json(["user"=> $user, "kpi"=> $kpi]);
        } catch (Exception $ex) {
            echo $ex;
            return response()->json([
                'error' => 'an error has occured',
                'description' => 'an error has occured please try again'
            ], 500);
        }
    }



    /**
     * edit user kpi
     * @param \Illuminate\Http\Request $req
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function edit_user_kpi(Request $req)
    {
        try {

            //validate request
            $validate = Validator::make($req->all(), [
                'user_id' => ['required'],
                'kpi_id' => ['required'],
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }

            //data is valid

            //get kpi and user
            $kpi = Kpi::findOrFail($req->kpi_id);
            $user = User::findOrFail($req->user_id);

            //update the pivot
            $kpi->users()->updateExistingPivot($user->id, [
                'review' => $req->review,
                'actual' => $req->actual,
            ]);
            return response()->json(["user"=> $user, "kpi"=> $kpi]);
        } catch (Exception $ex) {
            echo $ex;
            return response()->json([
                'error' => 'an error has occured',
                'description' => 'an error has occured please try again'
            ], 500);
        }
    }


    /**
     * find all kpis
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function get_all_kpis(Request $request)
    {
        try {
            $pageSize = $request->input('size', 10); // Default page size is 10
            $query = $request->input('query', ''); // Search query
            $page = $request->input('page', 1); //page 

            $kpi = Kpi::with('users')
                ->where('name', 'ilike',  "%$query%") //search insensitive
                ->orderBy('name', 'asc')
                ->paginate($pageSize, ["*"], "page", $page);
            return response()->json($kpi); //return response
        } catch (Exception $ex) {
            echo $ex;
            return response()->json([
                'error' => 'an error has occured',
                'description' => 'an error has occured please try again'
            ], 500);
        }
    }



       /**
     * find all kpis
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function get_user_performance(Request $request)
    {
        try {
        $user = $request->user()->load("kpis");

            return response()->json($user); //return response
        } catch (Exception $ex) {
            echo $ex;
            return response()->json([
                'error' => 'an error has occured',
                'description' => 'an error has occured please try again'
            ], 500);
        }
    }


}


