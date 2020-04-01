<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\User;
use Carbon\Carbon;

/**
 * 어드민 용.
 */
trait AdminTrait
{

    /**
     * 회원 리스트
     *
     * @return array
     */
    public function getUserList() : array
    {
        $task = User::with(['type', 'state', 'level'])->withCount([
            'activity',
            'read_book'
        ])->get();

        if($task->isNotEmpty()) {
            $taskResult = $task->toArray();

            $user_list = array_values(array_filter(array_map(function($element){
                return [
                    'id' => $element['id'],
                    'uuid' => $element['uuid'],
                    'email' => $element['email'],
                    'name' => $element['name'],
                    'type' => [
                        'code_id' => $element['type']['code_id'],
                        'code_name' => $element['type']['code_name'],
                    ],
                    'state' => [
                        'code_id' => $element['state']['code_id'],
                        'code_name' => $element['state']['code_name'],
                    ],
                    'level' => [
                        'code_id' => $element['level']['code_id'],
                        'code_name' => $element['level']['code_name'],
                    ],
                    'active' => $element['active'],
                    'activity_count' => $element['activity_count'],
                    'read_book_count' => $element['read_book_count'],
                    'created_at' => $element['created_at'],
                    'created_at_atring' => Carbon::parse($element['created_at'])->format('Y/m/d H:s'),
                ];
            }, $taskResult)));

			return $user_list;
        }

        return [];
    }

    /**
     * 회원 기본 정보.
     *
     * @param string $user_uuid
     * @return void
     */
    public function makeUserInfoByUUID(string $user_uuid)
    {
        $task = User::with(['type', 'state', 'level'])->withCount([
            'activity',
            'read_book',
            'upload_book'
        ])->where('uuid', $user_uuid)->get();

        if($task->isNotEmpty()) {
            $taskResult = $task->first()->toArray();
            return [
                'user_id' => $taskResult['id'],
                'user_uuid' => $taskResult['uuid'],
                'user_name' => $taskResult['name'],
                'user_email' => $taskResult['email'],
                'user_type' => $taskResult['type']['code_name'],
                'user_type_code' => $taskResult['type']['code_id'],
                'user_state' => $taskResult['state']['code_name'],
                'user_state_code' => $taskResult['state']['code_id'],
                'user_level' => $taskResult['level']['code_name'],
                'user_level_code' => $taskResult['level']['code_id'],
                'user_activity_state' => $taskResult['activity_state'],
                'user_active' => $taskResult['active'],
                'activity_count' => $taskResult['activity_count'],
                'read_book_count' => $taskResult['read_book_count'],
                'upload_book_count' => $taskResult['upload_book_count'],
                'user_created_at' => $taskResult['created_at'],
                'updated_at' => $taskResult['updated_at'],
                'created_at_string' => Carbon::parse($taskResult['created_at'])->format('Y/m/d H:s'),
                'updated_at_string' => Carbon::parse($taskResult['updated_at'])->format('Y/m/d H:s'),
                'email_verified_at_string' => Carbon::parse($taskResult['email_verified_at'])->format('Y/m/d H:s'),
            ];

        } else {
            return false;
        }
    }

    /**
     * uuid 로 사용자 active 업데이트
     *
     * @param [type] $user_uuid
     * @param [type] $active
     * @return void
     */
    public function updateUserActiveByUserUUID($user_uuid, $active)
    {
        return User::where('uuid', $user_uuid)->update(['active' => $active]);
    }
}
