<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
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
        return response()->json($params, 200);
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
