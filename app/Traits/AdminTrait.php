<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\User;
use App\Model\Book\Books;
use App\Model\Book\RecommendBooks;
use Carbon\Carbon;
use App\Model\Book\UserBookActivity;
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

    /**
     * 책 존재 여부.
     *
     * @param [type] $book_uuid
     * @return void
     */
    public function booksExits($book_uuid)
    {
        return Books::where([
            ['uuid', '=', $book_uuid],
        ])->exists();
    }

        /**
     * 책 존재 여부.
     *
     * @param [type] $book_uuid
     * @return void
     */
    public function booksExitsByid($book_id)
    {
        return Books::where([
            ['id', $book_id],
        ])->exists();
    }

    /**
     * 책 리스트
     *
     * @return array
     */
    public function getBooksList() : array
    {
        $task = Books::with(['user'])->withCount(['recommend'])->orderBy('id', 'DESC')->get();

        if($task->isNotEmpty()) {
            $taskResult = $task->toArray();

            $book_list = array_values(array_filter(array_map(function($element){
                return [
                    'id' => $element['id'],
                    'uuid' => $element['uuid'],
                    'title' => $element['title'],
                    'authors' => $element['authors'],
                    'isbn' => $element['isbn'],
                    'publisher' => $element['publisher'],
                    'thumbnail' => $element['thumbnail'],
                    'active' => $element['active'],
                    'user_name' => $element['user']['name'],
                    'contents' => $element['contents'],
                    'user_id' => $element['user']['id'],
                    'recommend' => ($element['recommend_count']) ? true : false,
                    'created_at_atring' => Carbon::parse($element['created_at'])->format('Y/m/d H:s'),
                ];
            }, $taskResult)));

            return $book_list;
        }

        return [];
    }

    /**
     * 추천 도서 리스트
     *
     * @return array
     */
    public function getRecommendBooksList(string $gubun) : array
    {
        $task = RecommendBooks::with(['books','books.user', 'gubun' => function($q) {
            $q->select('id', 'code_id', 'code_name');
        }])->orderBy('id', 'DESC')->get();

        if($task->isNotEmpty()) {
            $taskResult = $task->toArray();

            $user_list = array_values(array_filter(array_map(function($element){
                return [
                    'list_id' => $element['id'],
                    'book_id' => $element['book_id'],
                    'code_id' => $element['gubun']['code_id'],
                    'code_name' => $element['gubun']['code_name'],
                    'book_title' => $element['books']['title'],
                    'book_thumbnail' => $element['books']['thumbnail'],
                    'book_uuid' => $element['books']['uuid'],
                    'book_contents' => $element['books']['contents'],
                    'book_user_id' => $element['books']['user']['id'],
                    'book_user_name' => $element['books']['user']['name'],
                    'created_at_atring' => Carbon::parse($element['created_at'])->format('Y/m/d H:s'),
                ];
            }, $taskResult)));

            return $user_list;
        }

        return [];
    }

     /**
     * 책 존재 여부.
     *
     * @param [type] $book_uuid
     * @return void
     */
    public function recommendBooksExitsByid(int $list_id)
    {
        return RecommendBooks::where([
            ['id', $list_id],
        ])->exists();
    }

    public function recommendBooksExitsByBookid(int $book_id)
    {
        return RecommendBooks::where([
            ['book_id', $book_id],
        ])->exists();
    }

    public function deleteRecommendBook(int $book_id)
    {
        return RecommendBooks::where('book_id', $book_id)->delete();
    }
    public function createRecommendBook(int $user_id, string $gubun, int $book_id)
    {
        $task = RecommendBooks::create([
            'user_id' => $user_id,
            'gubun' => $gubun,
            'book_id' => $book_id,
        ]);
        if(!$task) {
			return false;
		}
        return $task->id;
    }

    public function getBooksActivityList(string $gubun)
    {
        $task = UserBookActivity::with(['books', 'gubun', 'user'])->where('gubun', $gubun)->get();

        if($task->isNotEmpty()) {
            $taskResult = $task->toArray();

            $list = array_values(array_filter(array_map(function($element){
                return [
                    'list_id' => $element['id'],
                    'list_uid' => $element['uid'],
                    'book_id' => $element['book_id'],
                    'book_title' => $element['books']['title'],
                    'book_thumbnail' => $element['books']['thumbnail'],
                    'user_id' => $element['user_id'],
                    'user_name' => $element['user']['name'],
                    'gubun' => $element['gubun']['code_id'],
                    'gubun_name' => $element['gubun']['code_name'],
                    'contents' => $element['contents'],
                    'created_at_string' => Carbon::parse($element['created_at'])->format('Y/m/d H:s'),
                ];
            }, $taskResult)));

            return $list;
        }

        return [];
    }

    public function bookActivityExitsByuuid(string $uid)
    {
        return UserBookActivity::where([
            ['uid', $uid],
        ])->exists();
    }
    public function deleteBookActivity(string $uid)
    {
        return UserBookActivity::where('uid', $uid)->delete();
    }
}
