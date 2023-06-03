<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct()
    {
        Config::set('auth.defaults.guard','admin-api');
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()) {
            return response()->json($validator->erros(), 422);
        }
        if(! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    /**
     * Register a User
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:admins',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = Admin::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'message' => 'Admin successfully registered',
            'user' => $user
        ], 201);
    }

    public function logout() {
        auth()->logout();
        return response()->json(['message' => ' Admin successfully signed out']);
    }

    public function userProfile(){
        return response()->json(auth()->user());
    }
    /**
     * Get the token array structure.
     * 
     * @param string $token
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => strtotime(date('Y-m-d H:i:s', strtotime("+60min"))),
            'user' => auth()->user()
        ]);
    }
}
