<?php

namespace App\Http\Controllers\api\v1\user;

use App\Http\Controllers\api\BaseController;
use Illuminate\Http\Request;

use App\Repositories\v1\UserRepository;

class UserController extends BaseController
{
    protected $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * 사용자 설정 정보.
     *
     * @param Request $request
     * @return void
     */
    public function setting(Request $request)
    {
        $task = $this->user->getSettingInfo();

        if($task['state'] == false) {
            return BaseController::defaultListNothingResponse($task['message']);
        } else {
            return BaseController::secondSuccessResponse($task['data']);
        }
    }

    /**
     * 활동 유무 업데이트
     *
     * @param Request $request
     * @return void
     */
    public function update_activity(Request $request)
    {
        $task = $this->user->update_activity($request);

        if($task['state']) {
            return BaseController::defaultSuccessCreateResponse();
        } else {
            return BaseController::defaultCreateFailResponse($task['message']);
        }
    }
}
