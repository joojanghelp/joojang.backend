<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Request;

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

        //TODO: 헤더 체크.. ( 클라이언트 코드 체크. )
	    if(empty($clientType) || !($clientType == "A01001" || $clientType == "A01002" || $clientType == "A01003"))
	    {
		    throw new \App\Exceptions\CustomException(__('auth.client_failed'));
        }

        //TODO: 우선 로그 저장 하기로.. 추후에 주석을 하든 세분화 해야함.
        $logid = date('Ymdhis');

        $logRoute = Route::current();
        $logRoutename = Route::currentRouteName();
        $logRouteAction = Route::currentRouteAction();

        $current_url = url()->current();
        $logHeaderInfo = json_encode($request->header());
        $logBodyInfo = json_encode($request->all());

        $logMessage = "ID:${logid} Current_url:${current_url} RouteName:${logRoutename} RouteAction:${logRouteAction} Header: {$logHeaderInfo} Body: ${logBodyInfo}";
        Log::channel('requestlog')->error($logMessage);

        return $next($request);
    }
}
