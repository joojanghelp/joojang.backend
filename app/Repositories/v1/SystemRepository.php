<?php
namespace App\Repositories\v1;

use App\Repositories\v1\SystemRepositoryInterface;
use App\Traits\SystemTrait;

class SystemRepository implements SystemRepositoryInterface
{
    use SystemTrait {
        SystemTrait::getCode as getCodeTrait;
        SystemTrait::getCodeGroupList as getCodeGroupListTrait;
    }

    public function start()
    {
        echo "::: BooksRepository start :::";
    }

    /**
     * 공통 코드?
     *
     * @param Request $request
     * @return array
     */
    public function attemptGetCode() : array
    {
        $codes = [];
        foreach ($this->getCodeTrait() as $element) :
            if($element['code_id']) {
                $codes[$element['group_id']][$element['code_id']] = [
                    'code_id' => $element['code_id'],
                    'code_name' => $element['code_name'],
                ];
            } else {
                $codes[$element['group_id']]['name'] = $element['group_name'];
            }
        endforeach;
        return [
            'state' => true,
            'data' => $codes
        ];
    }

    /**
     * 그룹코드 조회.
     *
     * @param string $group_id
     * @return void
     */
    public function attemptGroupCodeList(string $group_id)
    {

        $activitys = array_map(function($element) {
            // print_r($element);
            if(!empty($element)) {
                return [
                    'list_id' => $element['id'],
                    'code_id' => $element['code_id'],
                    'code_name' => $element['code_name'],
                ];
            }

        }, $this->getCodeGroupListTrait($group_id));

        if(empty($activitys)) {
            return [
                'state' => false,
                'message' => __('messages.error.nothing')
            ];
        }

        return [
            'state' => true,
            'data' => $activitys
        ];
    }
}
