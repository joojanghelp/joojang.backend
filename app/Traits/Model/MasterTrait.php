<?php
namespace App\Traits\Model;

use Illuminate\Support\Facades\DB;

use App\User;

use App\Model\Codes;

trait MasterTrait
{
    /**
     * 공통 코드 아이디로 코드명 명 전달.
     *
     * @param string $code_id
     * @return string
     */
    public function getCodeToName(string $code_id) : string
    {
        $task = Codes::where('code_id', $code_id)->get();
        if($task->isNotEmpty()) {
            $result = $task->first();
            return $result['code_name'];
        } else {
            return '';
        }
    }
}
