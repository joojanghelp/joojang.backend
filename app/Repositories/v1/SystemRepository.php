<?php
namespace App\Repositories\v1;

use App\Repositories\v1\SystemRepositoryInterface;
use App\Traits\SystemTrait;

class SystemRepository implements SystemRepositoryInterface
{
    use SystemTrait {
        SystemTrait::getCode as getCodeTrait;
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
}
