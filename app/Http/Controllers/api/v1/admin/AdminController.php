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

    public function user_list(Request $request, int $page)
    {
        $task = $this->admin->attemptUserList($request, $page);

        if($task['state'] == false) {
            return BaseController::defaultListNothingResponse($task['message']);
        } else {
            return BaseController::defaultPageListSuccessResponse($task['data']);
        }

    }
}
