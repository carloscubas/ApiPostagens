<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class AutorizacaoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(!$request->is('login') && \Auth::guest()) {

            $input = $request->all();

            if (array_key_exists("token", $input)) {

                $user = JWTAuth::toUser($input['token']);

                if($user == null){

                    return response()->json([
                        'message'   => 'User not found',
                    ], 401);

                }

            }else{
                return response()->json([
                    'message'   => 'Token invalid',
                ], 400);
            }

        }

        $request->attributes->add(['user' => $user]);

        return $next($request);
    }
}
