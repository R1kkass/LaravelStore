<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class RoleMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apy = JWTAuth::getPayload($request->header("Authorization"))->toArray();
        $users = DB::table('users')->where("email", $apy['email'])
            ->join('role', 'users.roleId', '=', 'role.id')->first();
        $o = $users->roleName === "ADMIN";
        
        if(!$o){
            return response()->json($o);

        }
        return $next($request);

    }
}
