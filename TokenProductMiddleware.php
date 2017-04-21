<?php

namespace App\Http\Middleware;

use Closure;

class TokenProductMiddleware
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
        if($request->session()->has('accessTokenProduct')){
            Session::flash('errToken', "1");
            return redirect()->route('config')->withTokens(1);
        }
        return $next($request);
    }
}
