<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
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

            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'name' => $request->name
            ]);

            $credentials = $request->only('email', 'password');
            $token = Auth::attempt($credentials);

            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        } catch (Exception $ex) {
            return response()->json($ex->getMessage(), 500);
        }
    }


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

            $credentials = $request->only('email', 'password');
            $token = Auth::attempt($credentials);

            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'invalid credentials'
                ], 400);
            }

            $user = Auth::user();
            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        } catch (Exception $ex) {
            return response()->json($ex->getMessage(), 500);
        }
    }

    public function logout(): JsonResponse
    {

        Auth::logout();
        return response()->json(['message' => 'logout succesful']);
    }

    public function refresh(): JsonResponse
    {
        return response()->json([
            'user' => Auth::user(),
            'token' => Auth::refresh()
        ]);
    }
}
