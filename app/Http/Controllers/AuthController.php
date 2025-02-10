<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PDOException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
  
    /**
     * user registration
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|mixed
     */
    public function register(Request $request): JsonResponse
    {

        try {
            $validate = Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'password' => ['required', 'min:5'],
                'name' => ['required']
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 400);
            }

            //data is valid

            //create new user
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'name' => $request->name
            ]);

            return response()->json([
                'user' => $user,
            ]);
        } catch (PDOException $ex) {
            // this exception throws when user is alrady registered with the same email
            if ($ex->getCode() == 23505) {
                return response()->json([
                    "error" => "an error has occured",
                    "description" => "user with email $request->email has already registered"
                ], 400);
            }
            return response()->json(["error" => "error", "description" => "an error has occured"], 400);
        } catch (Exception $ex) {
            return response()->json(["error" => "error", "description" => "an error has occured"], 500);
        }
    }


    /**
     *login the user
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|mixed
     */
    public function login(Request $request): JsonResponse
    {

        try {
            $validate = Validator::make($request->all(), [
                'email' => ['required', 'email'],
                'password' => ['required', 'min:5'],
            ]);

            if ($validate->fails()) {
                return response()->json($validate->errors(), 401);
            }

            //valid data

            //pass password and username for authentication
            $credentials = $request->only('email', 'password');

            //get the authentication token
            $token = Auth::attempt($credentials);

            // if the user is not valid return the error
            if (!$token) {
                return response()->json([
                    'error' => 'an error has occured',
                    'description' => 'invalid credentials'
                ], 400);
            }

            // valid user
            $user = Auth::user();
            //get expiration time of the token
            $expiration = JWTAuth::setToken($token)->getPayload()->get('exp');

            //construct response
            return response()->json([
                'token' => $token,
                'exp' => $expiration,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'an error has occured',
                'description' => 'an error has occured please try again'
            ], 400);
        }
    }

    /**
     * logout the user
     * @return JsonResponse|mixed
     */
    public function logout(): JsonResponse
    {

        Auth::logout();
        return response()->json(['message' => 'logout succesful']);
    }

    /**
     * refresh users tokens 
     * @return JsonResponse|mixed
     */
    public function refresh(): JsonResponse
    {
        try {
            //fefresh the the access token from authorization header
            $token = Auth::refresh();
            //get it's expieration time
            $expiration = JWTAuth::setToken($token)->getPayload()->get('exp');
            //construct the response
            return response()->json([
                'token' => $token,
                'exp' => $expiration,
            ]);
        } catch (Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 400);
        }
    }
}
