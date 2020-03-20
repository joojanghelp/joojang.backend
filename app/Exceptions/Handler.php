<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // print_r(get_class($exception));
        // print_r(typeof $exception($request));
        // return parent::render($request, $exception);
        $logid = date('Ymdhis'); // 로그 고유값.

    	// mysql 에러
	    if ($exception instanceof \PDOException)  // mysql Exception 따로 기록.
	    {
			$logMessage = "ID:{$logid} Code:{$exception->getCode()} Message:{$exception->getMessage()} File:{$exception->getFile()} Line:{$exception->getLine()}";
			Log::channel('pdoexceptionlog')->error($logMessage);
        }

        // 인증 에러.
        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return response()->json([
                'error_message' => __('auth.need_login'),
            ], 401);
        }

        /**
         * 기타.
         */
        if ($exception instanceof \App\Exceptions\CustomException) // 기타 Exception
	    {
            $logHeaderInfo = json_encode($request->header());
            $logMessage = "ID:{$logid} Header: {$logHeaderInfo} Code:{$exception->getCode()} Message:{$exception->getMessage()} File:{$exception->getFile()} Line:{$exception->getLine()}";
            Log::channel('customexceptionlog')->error($logMessage);

		    return $exception->render($request, $exception);
        }

        /**
         * 페이지 없는 요청 일떄.
         */
        if ($request->wantsJson() && $exception instanceof NotFoundHttpException)
	    {
            $logHeaderInfo = json_encode($request->header());
            $logMessage = "ID:{$logid} Header: {$logHeaderInfo} Code:{$exception->getCode()} Message:{$exception->getMessage()} File:{$exception->getFile()} Line:{$exception->getLine()}";
            Log::channel('customexceptionlog')->error($logMessage);

		    return response()->json([
                'error_message' => '알수 없는 요청 입니다.',
            ], 404);
        }

        // ajax 요청 일떄.
        if($request->wantsJson()) {

		    if(config('app.debug'))
		    {
			    return response()->json([
				    'error' => 'Exception Error.',
				    'error_class' => get_class($exception),
				    'error_message' => $exception->getMessage(),
			    ], 500);
		    }
		    else
		    {
			    return response()->json([
				    'error_message' => $exception->getMessage(),
			    ], 500);
		    }
        }

        // 일 반 웹 요청 일떄.
	    if ($this->isHttpException($exception)) {

		    if (view()->exists('errors.'.$exception->getCode()))
		    {
			    return response()->view('errors.'.$exception->getCode(), [
			    	'message' => config('app.debug') ? $exception->getMessage() : ''
			    ], $exception->getCode());
		    }

		    return response()->view('errors.500', [
			    'message' => config('app.debug') ? $exception->getMessage() : ''
		    ], 500);
	    }

	    return parent::render($request, $exception);
    }
}
