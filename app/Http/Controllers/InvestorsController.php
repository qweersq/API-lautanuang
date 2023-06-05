<?php

namespace App\Http\Controllers;

use App\Models\Investors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class InvestorsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api_investor', ['except' => ['login', 'register']]);
    // }
    public function __construct()
    {
        Config::set('auth.defaults.guard', 'subadmin-api');
    }
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        // jika kosong maka akan mengembalikan pesan error
        if (!$email || !$password) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter email or password'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->erros(), 422);
        }
        
        $investor = Investors::where('email', $email)->first();
        
        if (!$investor) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }
        if (!Hash::check($password, $investor->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    /**
     * Register a User
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'phone' => 'required|string|max:12',
            'email' => 'required|string|email|max:100|unique:investors',
            'password' => 'required|string|confirmed|min:8',
            'gender' => 'required|in:male,female',
            'address' => 'required',
            'birth_date' => 'required|date',
            'location_id' => 'required|integer',
            'nik' => 'required|unique:fisherman,nik|numeric',
            'npwp' => 'required',
            'identity_photo' => 'required',
            'bank_id' => 'required|integer',
            'register_date' => 'required',
            'balance' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = Investors::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        return response()->json([
            'message' => 'Investors successfully registered',
            'user' => $user
        ], 201);
    }



    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     * 
     * @param string $token
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => strtotime(date('Y-m-d H:i:s', strtotime("+60min"))),
            'user' => auth()->user()
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => ' Investors successfully signed out']);
    }

    public function count()
    {
        $total = Investors::all()->count();
        return response()->json([
            'status' => 'success',
            'total data' => $total
        ]);
    }
}


//     public function login(Request $request)
//     {
//         $credentials = $request->only('email', 'password');
//         $token = null;

//         try {
//             if (!$token = auth('api_investor')->attempt($credentials)) {
//                 return response()->json(['success' => false, 'message' => 'Invalid email or password'], 401);
//             }
//         } catch (JWTException $e) {
//             return response()->json(['success' => false, 'message' => 'Failed to create token'], 500);
//         }

//         return response()->json(['success' => true, 'token' => $token]);
//     }

//     public function register(Request $request)
//     {
//         $request->validate([
//             'name' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:investors',
//             'password' => 'required|string|min:8',
//         ]);

//         $investor = new Investors([
//             'name' => $request->input('name'),
//             'email' => $request->input('email'),
//             'password' => Hash::make($request->input('password')),
//         ]);

//         $investor->save();

//         return response()->json(['message' => 'Investors registered successfully'], 201);
//     }

//     // ...
// }
