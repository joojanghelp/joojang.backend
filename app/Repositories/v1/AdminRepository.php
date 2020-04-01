<?php
namespace App\Repositories\v1;

use App\Repositories\v1\AdminRepositoryInterface;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Request;

use Illuminate\Support\Facades\Auth;

use App\Traits\UserTrait;
use App\Traits\AdminTrait;
use App\Traits\MasterTrait;

class AdminRepository implements AdminRepositoryInterface
{
    protected $pageRow = 30;

    use UserTrait, AdminTrait, MasterTrait {
        UserTrait::getUserSettingInfo as getUserSettingInfoTrait;
        UserTrait::updateUserActivityState as updateUserActivityStateTrait;
        UserTrait::checkUserUUIDExists as checkUserUUIDExistsTrait;
        UserTrait::updateUsersByUserUUID as updateUsersByUserUUIDTrait;
        AdminTrait::getUserList as getUserListTrait;
        AdminTrait::makeUserInfoByUUID as makeUserInfoByUUIDTrait;
        AdminTrait::updateUserActiveByUserUUID as updateUserActiveByUserUUIDTrait;
        MasterTrait::paginateCollection as paginateCollectionTrait;
    }

    public function start()
    {
    }

    /**
     * 회원 리스트 생성.
     *
     * @param Request $request
     * @param integer $page
     * @return void
     */
    public function attemptUserList(Request $request, int $page)
    {
        $task = $this->getUserListTrait();

        if(!$task) {
            return [
                'state' => false,
                'message' => __('messages.error.nothing')
            ];
        }

        $taskResult = $this->paginateCollectionTrait($task, $this->pageRow, $page)->toArray();

        if($taskResult) {
            $taskResult['items'] = $taskResult['data'];
            unset($taskResult['data']);
        }

        return [
            'state' => true,
            'data' => $taskResult
        ];
    }

    /**
     * 회원 기본 정보 데이터 생성.
     *
     * @param Request $request
     * @param string $user_uuid
     * @return array
     */
    public function attemptGetUserInfo(Request $request, string $user_uuid) : array
    {
        $task = $this->makeUserInfoByUUIDTrait($user_uuid);

        if(!$task) {
            return [
                'state' => false,
                'message' => __('messages.error.nothing_user')
            ];
        }

        return [
            'state' => true,
            'data' => $task
        ];
    }

    /**
     * 회원 기본 정보 업데이트
     *
     * @param [type] $request
     * @param string $user_uuid
     * @return array
     */
    public function attemptGetUserInfoUpdate($request, string $user_uuid) : array
    {
        $validator = FacadesValidator::make($request->all(), [
			'user_email' => 'required',
			'user_name' => 'required',
			'user_type' => 'required',
			'user_state' => 'required',
			'user_level' => 'required',
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

        $task = $this->checkUserUUIDExistsTrait($user_uuid);
        if(!$task) {
            return [
                'state' => false,
                'message' => __('messages.error.nothing_user')
            ];
        }

        $updateObject = [
            'email' => $request->input('user_email'),
            'name' => $request->input('user_name'),
            'type' => $request->input('user_type'),
            'state' => $request->input('user_state'),
            'level' => $request->input('user_level'),
        ];

        $updateTask = $this->updateUsersByUserUUIDTrait($user_uuid, $updateObject);

        return [
            'state' => true,
            'data' => $task
        ];
    }

    /**
     * 사용자 활설 비활성.
     *
     * @param [type] $request
     * @return void
     */
    public function attemptUserActiveControl($request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'user_uuid' => 'required',
            'active' => 'required',
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

        $task_user_uuid = $request->input('user_uuid');
        $task_active = $request->input('active');

        $task = $this->checkUserUUIDExistsTrait($task_user_uuid);
        if(!$task) {
            return [
                'state' => false,
                'message' => __('messages.error.nothing_user')
            ];
        }

        $task = $this->updateUserActiveByUserUUIDTrait($task_user_uuid, $task_active);
        return [
            'state' => true,
            'data' => []
        ];
    }
}
