<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Cookie;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'regist', 'refreshed']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        $user = User::where("email", $credentials["email"])->first();
        if (! $token = auth()->claims(['email' => $credentials["email"], "id" => $user->id])->setTTL(60*24*30)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $token2 = auth()->claims(['email' => $credentials["email"], 'password' => $credentials["password"]])->setTTL(7200)->attempt($credentials);

        return $this->respondWithToken($token, $token2, $credentials);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function regist(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "secondName" => "required|string|max:255",
            "lastName" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users",
            "password" => "required|string|min:6"
        ]);
       $regist = DB::table("users")->insert([
            "password"=>Hash::make($request["password"]), 
            "name" => "$request[secondName] $request[name] $request[lastName]", 
            "email" => $request["email"],
            "roleId" => 1
        ]); 
        return $regist;
    }

    public function refreshed(Request $request)
    {
        $tok=$request->cookie("refresh_token");
        $apy = JWTAuth::getPayload($tok)->toArray();

        $costil = [
            "email" => $apy['email'],
            "password" => $apy['password']
        ];

        $token = auth()->claims(['email' => $costil["email"]])->setTTL(60)->attempt($costil);
        return $this->respondWithToken($token, $tok, $costil);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $token2, $password)
    {
        
        return response()
            ->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60*24*30,
            ]);
        //    ->cookie("password", Crypt::encryptString($password['password']))
        //    ->cookie("email", Crypt::encryptString($password["email"]));
        }

    protected function respondOnlyAccess($token)
    {
        return response()
            ->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
    }
}