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
}
