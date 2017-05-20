<?php

namespace App\Http\Middleware;

use Closure;
use Session;
class TokenMiddleware
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
        if(!$request->session()->has('access_token')){
            Session::flash('errToken', "1");
           return redirect()->route('config');
        }
        return $next($request);
    }
}
