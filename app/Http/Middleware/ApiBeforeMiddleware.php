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

	    if(empty($clientType) || !($clientType == "A02001" || $clientType == "A02002" || $clientType == "A02003"))
	    {
		    throw new \App\Exceptions\CustomException(__('auth.client_failed'));
        }

        return $next($request);
    }
}
