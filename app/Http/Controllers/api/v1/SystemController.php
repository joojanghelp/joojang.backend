<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\api\BaseController;
use Illuminate\Http\Request;

use App\Repositories\v1\SystemRepository;

class SystemController extends BaseController
{
    protected $system;

    public function __construct(SystemRepository $system)
    {
        $this->system = $system;
    }

    /**
     * 공통 코드 리스트
     *
     * @param Request $request
     * @return void
     */
    public function commoncode(Request $request)
    {
        $task = $this->system->attemptGetCode();
        if($task['state'] == false) {
            return BaseController::defaultListNothingResponse($task['message']);
        } else {
            return BaseController::defaultListSuccessResponse($task['data']);
        }
    }

    /**
     * 그룹 코드 리스트 조회.
     *
     * @param string $group_code
     * @return void
     */
    public function commoncode_group_list(string $group_code)
    {
        $task = $this->system->attemptGroupCodeList($group_code);
        if($task['state'] == false) {
            return BaseController::defaultListNothingResponse($task['message']);
        } else {
            return BaseController::defaultListSuccessResponse($task['data']);
        }
    }

    /**
     * 시스템 기본 데이터.
     *
     * @param Request $request
     * @return void
     */
    public function basedata(Request $request)
    {
        $task = $this->system->attemptBaseData($request);
        if($task['state'] == false) {
            return BaseController::defaultListNothingResponse($task['message']);
        } else {
            return BaseController::defaultListSuccessResponse($task['data']);
        }
    }
}
