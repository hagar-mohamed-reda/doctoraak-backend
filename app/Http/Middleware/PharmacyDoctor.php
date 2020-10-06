<?php

namespace App\Http\Middleware;

use Closure;

class PharmacyDoctor
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
            return redirect("pharmacydoctordashboard/login");
        
        return $next($request);
    }
}
