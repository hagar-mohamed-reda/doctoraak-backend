<?php

namespace App\Http\Middleware;

use Closure;

class Insurance
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
        if (!session("insurance"))
            return redirect("insuranceuserdashboard/login");
        
        return $next($request);
    }
}
