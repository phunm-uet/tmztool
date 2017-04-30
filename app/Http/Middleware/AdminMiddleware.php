<?php

namespace App\Http\Middleware;
use Auth;
use Closure;

class AdminMiddleware
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
        if(Auth::user()->department->slug != "admin"){
            $slug = Auth::user()->department->slug;
            return redirect("/$slug"); 
        }
        return $next($request);
    }
}
