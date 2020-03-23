<?php
namespace App\Traits;

use App\Model\EmailAuth;
use App\User;

trait EmailAuthTrait
{
    /**
     * 사용자 이메일 인증 처리.
     *
     * @param string $authCode
     * @return array
     */
    function webEmailAuthPageCheckTraitFunc(string $authCode) : array
    {
        $task = EmailAuth::with('user')->where('auth_code', $authCode);

        if($task->get()->isNotEmpty())
		{
            $taskResult = $task->first()->toArray();

            if(!empty($taskResult['verified_at']) || !empty($taskResult['user']['email_verified_at'])) {
                return [
                    'state' => false,
                    'message' =>  __('auth.email_auth.already_verified')
                ];
            }
            $time = \Carbon\Carbon::now();
            $user_uuid = $taskResult['user_uuid'];

            if(EmailAuth::where('user_uuid', $user_uuid)->update(['verified_at' => $time]))
            {
                User::where('uuid', $user_uuid)->update([
                    'email_verified_at' => $time,
                    'state' => 'A10010'
                ]);

                return [
                    'state' => true
                ];
            }

            return [
                'state' => false,
                'message' => __('message.default.error')
            ];
		}

        return [
            'state' => false,
            'message' =>  __('auth.email_auth.failed_auth_email_code')
        ];
    }
}
