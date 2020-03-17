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
	public function defaultSuccessResponse(array $params)
	{
		$response = [
			'message' => (isset($params['message']) && $params['message']) ? $params['message'] : __('messages.default.success')
		];


		if(isset($params['data']) && $params['data'])
		{
			$response['data'] = $params['data'];
		}

		if(isset($params['info']) && $params['info'])
		{
			$response['info'] = $params['info'];
		}

		$code = (isset($params['code']) && $params['code']) ? $params['code'] : 200;

		return response()->json($response, $code);
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

    /**
	 * 기본 성공 응답 (생성)
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
	 * 기본 에러 응답 (생성 실패)
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
     * 기본 성공 응답 ( 리스트용 )
     *
     * @return \Illuminate\Http\Response
     */
    public function defaultListSuccessResponse(array $params)
    {
        return response()->json([
            'items' => $params
        ], 200);
    }

    /**
     * 에러는 아니지만 데이터가 없을때.
     *
     * @param string $params
     * @return void
     */
    public function defaultListNothingResponse(string $params)
    {
        return response()->json([
            'message' => $params
        ], 200);
    }
}
