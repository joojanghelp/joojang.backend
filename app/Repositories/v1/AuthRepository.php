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

class AuthRepository implements AuthRepositoryInterface
{
    public function start() {
        echo "start";
    }

    public function test()
    {
        echo "::test::";
    }

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
        $emailObject->auth_url = url('/front/v1/auth/email_auth?code='.$auth_code);

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
}
