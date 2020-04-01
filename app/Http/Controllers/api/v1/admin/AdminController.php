<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\api\BaseController;
use Illuminate\Http\Request;

use App\Repositories\v1\AdminRepository;

class AdminController extends BaseController
{
    protected $admin;

    public function __construct(AdminRepository $admin)
    {
        $this->admin = $admin;
    }

    /**
     * 회원 리스트
     *
     * @param Request $request
     * @param integer $page
     * @return void
     */
    public function user_list(Request $request, int $page)
    {
        $task = $this->admin->attemptUserList($request, $page);

        if($task['state'] == false) {
            return BaseController::defaultListNothingResponse($task['message']);
        } else {
            return BaseController::defaultPageListSuccessResponse($task['data']);
        }
    }

    /**
     * 회원 정보.
     *
     * @param Request $request
     * @param string $user_uuid
     * @return void
     */
    public function user_info(Request $request, string $user_uuid)
    {
        $task = $this->admin->attemptGetUserInfo($request, $user_uuid);

        if($task['state'] == false) {
            return BaseController::defaultErrorResponse([
                'message' => $task['message']
            ]);
        } else {
            return BaseController::secondSuccessResponse($task['data']);
        }
    }

    /**
     * 회원 기본 정보 업데이트
     *
     * @param Request $request
     * @param string $user_uuid
     * @return void
     */
    public function user_info_update(Request $request, string $user_uuid)
    {
        $task = $this->admin->attemptGetUserInfoUpdate($request, $user_uuid);

        if($task['state'] == false) {
            return BaseController::defaultErrorResponse([
                'message' => $task['message']
            ]);
        } else {
            return BaseController::defaultSuccessCreateResponse();
        }
    }

    /**
     * 사용자 활성 컨트롤.
     *
     * @param Request $request
     * @return void
     */
    public function user_active(Request $request)
    {
        $task = $this->admin->attemptUserActiveControl($request);

        if($task['state'] == false) {
            return BaseController::defaultErrorResponse([
                'message' => $task['message']
            ]);
        }

        return BaseController::defaultSuccessCreateResponse();
    }

    /**
     * 책 있는지 여부.
     *
     * @param [type] $book_uuid
     * @return void
     */
    public function book_exits($book_uuid)
    {
        $task = $this->admin->attemptBookExits($book_uuid);

        return BaseController::defaultSuccessResponse([
            'info' => $task['data']
        ]);
    }
}
