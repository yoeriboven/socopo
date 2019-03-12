<?php

namespace App\Http\Middleware;

class AjaxOnly
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @return mixed
    */
    public function handle($request, \Closure $next)
    {
        // Disable middleware if we're running tests
        if (app()->environment() == 'testing') {
            return $next($request);
        }

        // Restrict access if page is accessed through the browser
        if (! $request->ajax()) {
            abort(403, 'Browser access forbidden.');
        }

        return $next($request);
    }
}
