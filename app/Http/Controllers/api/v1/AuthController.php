<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController;

use App\Repositories\v1\AuthRepository;

class AuthController extends BaseController
{
    protected $auth;

    public function __construct(AuthRepository $auth)
    {
        $this->auth = $auth;
    }

    /**
     * 사용자 로그인.
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request) {

        $result = $this->auth->attemptLogin($request);

        if($result['state']) {
			return $this->firstSuccessResponse([
				'data' => $result['data']
			]);
		} else {
			return $this->defaultErrorResponse([
				'message' => $result['message']
			]);
		}
    }

    /**
     * 사용자 가입.
     */
    public function register(Request $request)
    {
        $result = $this->auth->attemptRegister($request);

        if($result['state'])
		{
			return $this->defaultSuccessResponse([
				'message' => __('messages.success.registed'),
				'info' => $result['data']
			]);
		}
		else
		{
			return $this->defaultErrorResponse([
				'message' => $result['message']
			]);
		}
    }

    /**
     * 사용자 토큰 리프레쉬
     *
     * @param Request $request
     * @return void
     */
    public function refresh_token(Request $request)
    {
        $taskResult = $this->auth->attemptTokenRefresh($request);

        if($taskResult['state']) {
			return $this->firstSuccessResponse([
				'data' => $taskResult['data']
			]);
		} else {
			return $this->defaultErrorResponse([
				'message' => $taskResult['message']
			]);
		}
    }


}
