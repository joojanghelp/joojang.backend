<?php
namespace App\Repositories\v1;

use App\Repositories\v1\AuthRepositoryInterface;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

use App\User; // 사용자 모델
use App\Model\EmailAuth;
use App\Helpers\MasterHelper;
use App\Mail\EmailMaster;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route as FacadesRoute;

use App\Traits\PassportTrait;

class AuthRepository implements AuthRepositoryInterface
{
    use PassportTrait {
        PassportTrait::getNewToken as getNewTokenTrait;
    }

    public function start() {

    }

    /**
     * 사용자 회원 가입.
     *
     * @param [type] $request
     * @return void
     */
    public function attemptRegister($request)
    {
        $validator = FacadesValidator::make($request->all(), [
			'name' => 'required|unique:users',
			'email' => 'required|email|unique:users',
			'password' => 'required'
        ]);

        if( $validator->fails() )
		{
            $errorMessage = "";
            foreach($validator->getMessageBag()->all() as $element):
                $errorMessage .= $element."\n";
            endforeach;
			return [
				'state' => false,
				'message' => $errorMessage
			];
        }

        $newUserUUID = MasterHelper::GenerateUUID();
        $auth_code = Str::random(80);

        $emailObject = new \stdClass();
        $emailObject->category = "user_email_auth";
        $emailObject->receiverName = $request->input('name');
        $emailObject->receiver = $request->input('email');
        $emailObject->auth_code = $auth_code;
        $emailObject->auth_url = url('/web/v1/auth/email_auth?code='.$auth_code);

        Mail::to($request->input('email'))->send(new EmailMaster($emailObject));

        if(Mail::failures())
        {
            return [
                'state' => false,
                'message' => __('messages.default.error')
            ];
        }
        else
        {
            $userAddResult = User::create([
            	'uuid' => $newUserUUID,
            	'type' => $request->header('request-client-type'),
            	'name' => $request->input('name'),
            	'email' => $request->input('email'),
            	'password' => bcrypt($request->input('password')),
            ]);

            EmailAuth::create([
				'user_uuid' => $newUserUUID,
				'auth_code' => $auth_code,
			]);

            return [
                'state' => true,
                'data' => [
                    'uuid' => $newUserUUID
                ]
            ];
        }
    }

    /**
     * 사용자 로그인.
     *
     * @param [type] $request
     * @return void
     */
    public function attemptLogin($request)
    {
        if(!Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            return [
				'state' => false,
				'message' => __('auth.failed')
			];
        }

        $user = Auth::user();

        $tokenResult = $this->getNewTokenTrait($request->input('email'), $request->input('password'));

        $user_name = $user['name'];
        $user_state = $user['state'];
        $user_active = $user['active'];

        if($user_active != 'Y') // 사용자 상태 체크
        {
            return [
                'state' => false,
                'message' => __('auth.login.not_active_user')
            ];
        }

        if($user_state == 'A10000') // 사용자 대기 체크
        {
            return [
                'state' => false,
                'message' => __('auth.login.wait_user')
            ];
        }

        $returnData = [
            'token_type' => $tokenResult['token_type'],
            'expires_in' => $tokenResult['expires_in'],
            'access_token' => $tokenResult['access_token'],
            'refresh_token' => $tokenResult['refresh_token'],
            'user_name' => $user_name
        ];

        return [
            'state' => true,
            'data' => $returnData
        ];
    }

    /**
     * 사용자 토큰 리프레쉬.
     *
     * @param [type] $request
     * @return array
     */
    public function attemptTokenRefresh($request) : array
    {
        if(!$request->input('refresh_token')) {
            return [
                'state' => false,
                'message' => __('messages.error.token_nothing')
            ];
        }
        $taskResult = self::getRefreshTokenTrait($request->input('refresh_token'));

        if(!$taskResult['state']) {
            return [
                'state' => false,
                'message' => $taskResult['message']
            ];
        }

        return [
            'state' => true,
            'data' => $taskResult['token']
        ];
    }
}
