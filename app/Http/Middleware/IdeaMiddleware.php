<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IdeaMiddleware
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
        if(Auth::user()->department->slug != 'idea') {
                $slug = Auth::user()->department->slug;
                return redirect("/$slug"); 
        } else {
            return $next($request);
        }
    }
}
