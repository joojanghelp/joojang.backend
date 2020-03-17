<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
	 * 기본 성공 응답
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function defaultSuccessCreateResponse()
	{
		return response()->json([
            'messgae' => __('messages.default.do_success')
        ], 201);
	}

    /**
	 * 기본 성공 응답
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function defaultCreateFailResponse(string $message)
	{
		return response()->json([
            'messgae' => $message
        ], 400);
	}
    /**
     * 기본 성공 응답 ( 바디만 처리 )
     *
     * @return \Illuminate\Http\Response
     */
    public function firstSuccessResponse(array $params)
    {
        return response()->json($params['data'], 200);
    }

	/**
	 * 기본 에러 응답
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function defaultErrorResponse(array $params)
	{
        $errorMessages = "";

		if(isset($params['message']) && $params['message'])
		{
			$response['error_message'] = $params['message'];
		}
		else
		{
			$response['error'] = __('messages.default.error');
		}

		$code = (isset($params['code']) && $params['code']) ? $params['code'] : 401;

		if(!empty($errorMessages)){
			$response['data'] = $errorMessages;
		}

		return response()->json($response, $code);
	}
}
