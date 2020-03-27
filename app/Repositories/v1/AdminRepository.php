<?php
namespace App\Repositories\v1;

use App\Repositories\v1\AdminRepositoryInterface;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Str;

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
        AdminTrait::getUserList as getUserListTrait;
        MasterTrait::paginateCollection as paginateCollectionTrait;
    }

    public function start()
    {
    }

    public function attemptUserList($request, int $page)
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
}
