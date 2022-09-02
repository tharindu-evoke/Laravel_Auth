<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\User; 
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */ 
    public function __construct()
    { 
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string' 
        ]);

        if ($validator->fails()) {
            return response()->json("Validation Failed!", 400);
        }

        $user = User::create(
            array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            )
        );

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json("Validation Failed!", 400);
        }

        $token_validity = (24 * 60);
        $users = DB::table('users')
            ->select('name', 'email', 'password')
            ->where('email', 'LIKE', $request->email)
            ->get();

        if($users != '[]'){
            auth()->factory()->setTTL($token_validity);
            if(!$token = auth()->attempt(['email' => $request->email, 'password' => $request->password])){ 
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            else{
                return $this->respondWithToken($token);
            }
        }
        else{
            return response()->json("Email not found!", 404);
        }

    } 

    public function profile(){
        return response()->json($this->guard()->user());
    }

    public function logout(){
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function refresh(){
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token){ 
}