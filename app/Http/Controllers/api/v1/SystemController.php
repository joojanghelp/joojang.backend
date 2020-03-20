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
}
