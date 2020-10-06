<?php

namespace App\Http\Middleware;

use Closure;

class RadiologyDoctor
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
        if (!session("doctor"))
            return redirect("radiologydoctordashboard/login");
        
        return $next($request);
    }
}
