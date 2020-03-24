<?php
namespace App\Traits;

use App\Model\Codes;

trait SystemTrait
{
    /**
     * 공통 코드 리스트
     *
     * @return void
     */
    public function getCode()
    {
        return Codes::all()->toArray();
    }

    /**
     * 그룹코드 조회용.
     *
     * @param string $group_id
     * @return void
     */
    public function getCodeGroupList(string $group_id) : array
    {
        $task = Codes::whereNotNull('code_id')->where('group_id', $group_id)->where('active', 'Y')->get();
        if($task->isNotEmpty()) {
            return $task->toArray();
        } else {
            return [];
        }
    }
}
