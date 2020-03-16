<?php

namespace App\Http\Middleware;

use Closure;

class ApiBeforeMiddleware
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
        $clientType = $request->header('request-client-type');

	    if(empty($clientType) || !($clientType == "A01001" || $clientType == "A01002" || $clientType == "A01003"))
	    {
		    throw new \App\Exceptions\CustomException(__('auth.client_failed'));
        }

        return $next($request);
    }
}
