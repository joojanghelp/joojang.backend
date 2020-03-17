<?php

namespace App\Exceptions;

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
        // return parent::render($request, $exception);
        $logid = date('Ymdhis'); // 로그 고유값.

    	//TODO: Exception Control
	    if ($exception instanceof \PDOException)  // mysql Exception 따로 기록.
	    {
			$logMessage = "ID:{$logid} Code:{$exception->getCode()} Message:{$exception->getMessage()} File:{$exception->getFile()} Line:{$exception->getLine()}";
			Log::channel('pdoexceptionlog')->error($logMessage);

	    }

        if ($exception instanceof \App\Exceptions\CustomException) // 기타 Exception
	    {
            $logHeaderInfo = json_encode($request->header());
            $logMessage = "ID:{$logid} Header: {$logHeaderInfo} Code:{$exception->getCode()} Message:{$exception->getMessage()} File:{$exception->getFile()} Line:{$exception->getLine()}";
            Log::channel('customexceptionlog')->error($logMessage);

		    return $exception->render($request, $exception);
        }

	    if ($this->isHttpException($exception)) {  // 일 반 웹 요청 일떄.

		    if (view()->exists('errors.'.$exception->getStatusCode($exception)))
		    {
			    return response()->view('errors.'.$exception->getStatusCode($exception), [
			    	'message' => config('app.debug') ? $exception->getMessage() : ''
			    ], $exception->getStatusCode($exception));
		    }

		    return response()->view('errors.500', [
			    'message' => config('app.debug') ? $exception->getMessage() : ''
		    ], 500);
	    }
	    else
	    {
	    	// ajax 요청 일떄.
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

	    return parent::render($request, $exception);
    }
}
