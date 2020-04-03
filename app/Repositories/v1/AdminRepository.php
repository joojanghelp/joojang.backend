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
use App\Traits\Model\BooksTrait;

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
        AdminTrait::getBooksList as getBooksListTrait;
        AdminTrait::booksExits as booksExitsTrait;
        AdminTrait::getRecommendBooksList as getRecommendBooksListTrait;
        MasterTrait::paginateCollection as paginateCollectionTrait;
        AdminTrait::recommendBooksExitsByid as recommendBooksExitsByidTrait;
        AdminTrait::deleteRecommendBook as deleteRecommendBookTrait;
        AdminTrait::recommendBooksExitsByBookid as recommendBooksExitsByBookidTrait;
        AdminTrait::createRecommendBook as createRecommendBookTrait;
        AdminTrait::getBooksActivityList as getBooksActivityListTrait;
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

    /**
     * 책 존재 여부.
     *
     * @param [type] $book_uuid
     * @return void
     */
    public function attemptBookExits($book_uuid)
    {
        return [
            'state' => true,
            'data' => $this->booksExitsTrait($book_uuid)
        ];
    }

    public function attemptBooksList(Request $request, int $page)
    {
        $task = $this->getBooksListTrait();

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

    public function attemptRecommendBooksList(Request $request, string $gubun, int $page)
    {
        $task = $this->getRecommendBooksListTrait($gubun);

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

    public function attemptRecommendBooksDelete($request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'book_id' => 'required',
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

        $book_id = $request->input('book_id');

        $exitsTask = $this->recommendBooksExitsByBookid($book_id);
        if(!$exitsTask) {
            return [
                'state' => false,
                'message' => __('messages.error.nothing')
            ];
        }

        $deleteTask = $this->deleteRecommendBookTrait($book_id);

        if(!$deleteTask) {
            return [
                'state' => false,
                'message' => __('messages.default.error')
            ];
        }
        return [
            'state' => true
        ];
    }

    public function attemptRecommendBooksCreate($request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'book_id' => 'required',
            'gubun' => 'required',
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

        $book_id = $request->input('book_id');
        $gubun = $request->input('gubun');
        $user_id = Auth::id();

        $exitsTask = $this->booksExitsByid($book_id);
        if(!$exitsTask) {
            return [
                'state' => false,
                'message' => __('messages.error.nothing')
            ];
        }

        $exitsTask = $this->recommendBooksExitsByBookidTrait($book_id);
        if($exitsTask) {
            return [
                'state' => false,
                'message' => __('messages.error.exits')
            ];
        }

        $createTask = $this->createRecommendBookTrait($user_id, $gubun, $book_id);

        if(!$createTask) {
            return [
                'state' => false,
                'message' => __('messages.default.error')
            ];
        }
        return [
            'state' => true
        ];
    }

    public function attemptBooksActivityList(Request $request, string $gubun, int $page)
    {
        $task = $this->getBooksActivityListTrait($gubun);

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

    public function attemptBookActivityDelete($request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'activity_uuid' => 'required',
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

        $activity_uuid = $request->input('activity_uuid');

        $exitsTask = $this->bookActivityExitsByuuid($activity_uuid);
        if(!$exitsTask) {
            return [
                'state' => false,
                'message' => __('messages.error.nothing')
            ];
        }

        $deleteTask = $this->deleteBookActivity($activity_uuid);

        if(!$deleteTask) {
            return [
                'state' => false,
                'message' => __('messages.default.error')
            ];
        }
        return [
            'state' => true
        ];
    }

}
