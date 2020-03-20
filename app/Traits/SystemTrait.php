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
}
