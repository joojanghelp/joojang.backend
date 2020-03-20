<?php
namespace App\Repositories\v1;

use App\Repositories\v1\UserRepositoryInterface;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;

use App\Traits\UserTrait;

class UserRepository implements UserRepositoryInterface
{
    use UserTrait {
        UserTrait::getUserSettingInfo as getUserSettingInfoTrait;
        UserTrait::updateUserActivityState as updateUserActivityStateTrait;
    }


    public function start()
    {
    }

    /**
     * 사용자 설정 정보.
     *
     * @return array
     */
    public function getSettingInfo() : array
    {
        $user_id = Auth::id();

        $task = $this->getUserSettingInfoTrait($user_id);

        if(!$task) {
            return [
                'state' => false,
                'message' => __('messages.error.nothing')
            ];
        }

        return [
            'state' => true,
            'data' => [
                'user_id' => $task['id'],
                'uuid' => $task['uuid'],
                'email' => $task['email'],
                'name' => $task['name'],
                'type' => [
                    'code_id' => $task['type']['code_id'],
                    'code_name' => $task['type']['code_name']
                ],
                'state' => [
                    'code_id' => $task['state']['code_id'],
                    'code_name' => $task['state']['code_name']
                ],
                'level' => [
                    'code_id' => $task['level']['code_id'],
                    'code_name' => $task['level']['code_name']
                ],
                'activity_state' => $task['activity_state'],
                'activity_count' => $task['activity_count'],
                'read_book_count' => $task['read_book_count'],
            ]
        ];
    }

    /**
     * 사용자 활동 유무 업데이트
     *
     * @param [type] $request
     * @return array
     */
    public function update_activity($request) : array
    {
        $validator = FacadesValidator::make($request->all(), [
			'activity_state' => 'required',
        ]);

        if( $validator->fails() ) {
            $errorMessage = "";
            foreach($validator->getMessageBag()->all() as $element):
                $errorMessage .= $element."\n";
            endforeach;
			return [
				'state' => false,
				'message' => $errorMessage
			];
        }

        $task = $this->updateUserActivityStateTrait(Auth::id(), $request->input('activity_state'));

        if(!$task) {
            return [
                'state' => false,
                'message' => __('messages.default.error')
            ];
        }

        return [
            'state' => true,
            'message' => __('messages.default.do_success')
        ];
    }
}
