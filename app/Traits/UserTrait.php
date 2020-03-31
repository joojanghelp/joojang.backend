<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\User;

trait UserTrait
{
    /**
     * 사용자 세팅 페이지 정보.
     *
     * @return void
     */
    public function getUserSettingInfo(int $user_id)
    {
        return User::with(['type', 'state', 'level'])->withCount([
            'activity',
            'read_book'
        ])->where('id', $user_id)->get()->first()->toArray();
    }

    /**
     * 활동 공개 유무 업데이트
     *
     * @param integer $user_id
     * @param string $activity
     * @return void
     */
    public function updateUserActivityState(int $user_id, string $activity)
    {
        return User::where('id' ,$user_id)->update(['activity_state' => $activity]);
    }

    /**
     * uuid 를 이용 회원 있는지 체크
     *
     * @param string $user_uuid
     * @return void
     */
    public function checkUserUUIDExists(string $user_uuid)
    {
        return User::where('uuid', $user_uuid)->exists();
    }

    /**
     * 회원 uuid 로 정보 업데이트
     *
     * @param [type] $user_uuid
     * @param [type] $dateObject
     * @return void
     */
    public function updateUsersByUserUUID($user_uuid, $dateObject)
    {
        return User::where('uuid', $user_uuid)->update($dateObject);
    }
}
