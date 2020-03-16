<?php

namespace App\Http\Controllers\web\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\EmailAuthTrait;

class AuthController extends Controller
{
    use EmailAuthTrait;

    public function email_auth(Request $request)
    {
		$input = $request->only('code');
        $authCode = $input['code'];
        $taskResult = $this->webEmailAuthPageCheckTraitFunc($authCode);
        $viewData['state'] = $taskResult['state'];
        $viewData['message'] = (isset($taskResult['message']) && $taskResult['message']) ? $taskResult['message'] : '정상 처리 하였습니다.';

        return view('web.v1.auth/email_auth', $viewData);
    }
}
