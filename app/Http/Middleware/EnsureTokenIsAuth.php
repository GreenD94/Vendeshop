<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\Responser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Auth;

class EnsureTokenIsAuth
{
    use Responser;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {


        if (!$request->bearerToken()) return $this->errorResponse(null, 'Unauthenticated', 401);
        $token = explode('|', $request->bearerToken())[1];
        $personalAccessTokenspersonalAccessTokens = DB::table('personal_access_tokens')->where('token', hash('sha256',  $token))->first();
        if (!$personalAccessTokenspersonalAccessTokens) return $this->errorResponse(null, 'Unauthenticated', 401);
        $user = User::find($personalAccessTokenspersonalAccessTokens->tokenable_id);
        if (!$user) return $this->errorResponse(null, 'Unauthenticated', 401);
        Auth::setUser($user);
        return $next($request);
    }
}
