<?php
namespace App\Traits\Model;

use Illuminate\Support\Facades\DB;

use App\User;

use App\Model\Book\UsersBooks;
use App\Model\Book\Books;
use App\Model\Book\RecommendBooks;
use App\Model\Book\UserReadBooks;
use App\Model\Book\UserBookActivity;
use Carbon\Carbon;
trait BooksTrait
{

    /**
     * 책 데이터 있으면 업데이트 없으면 생성.
     *
     * @param array $params
     * @return void
     */
    public function createBooks(array $params) : int
    {
        $task = Books::updateOrCreate(
            [
                'uuid' => $params['uuid']
            ], [
                'user_id' => $params['user_id'],
                'authors' => $params['authors'],
                'contents' => $params['contents'],
                'isbn' => $params['isbn'],
                'publisher' => $params['publisher'],
                'thumbnail' => $params['thumbnail'],
                'title' => $params['title'],
            ]
        );
        if(!$task) {
			return false;
		}
        return $task->id;
    }

    /**
     * 등록되어 있는 책인지 체크
     *
     * @param integer $user_id
     * @param integer $book_id
     * @return void
     */
    public function userBooksExits(int $user_id, int $book_id)
    {
        $task = UsersBooks::where([
            ['user_id', '=', $user_id],
            ['book_id', '=', $book_id],
        ])->get();

        if($task->isNotEmpty()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 사용자 책 등록.
     *
     * @param integer $user_id
     * @param integer $book_id
     * @return void
     */
    public function createUserBooks(int $user_id, int $book_id)
    {
        $task = UsersBooks::create([
            'user_id' => $user_id,
            'book_id' => $book_id,
        ]);
        if(!$task) {
			return false;
		}
        return $task->id;
    }

    /**
     * 읽은책 등록.
     *
     * @param integer $user_id
     * @param integer $book_id
     * @return void
     */
    public function createBooksRead(int $user_id, int $book_id)
    {
        $task = UserReadBooks::create([
            'user_id' => $user_id,
            'book_id' => $book_id,
        ]);
        if(!$task) {
			return false;
		}
        return $task->id;
    }

    /**
     * 읽은 목록에 있는지 체크.
     *
     * @param integer $user_id
     * @param integer $book_id
     * @return void
     */
    public function checkBooksReads(int $user_id, int $book_id)
    {
        return UserReadBooks::where([
            ['user_id', '=', $user_id],
            ['book_id', '=', $book_id],
        ])->exists();
    }

    /**
     * 사용자 등록 책 리스트.
     *
     * @param integer $user_id
     * @return void
     */
    public function getUserBooks(int $user_id) : array
    {
        return UsersBooks::with('books')->where('user_id', $user_id)->get()->toArray();
    }

    /**
     * 추천 도서 목록
     *
     * @return void
     */
    public function getRecommendBooks()
    {
        return RecommendBooks::with('books', 'gubun', 'readbookable')->where('active', 'Y')->get()->toArray();
    }

    /**
     * 추천 도서 목록 중 사용자가 읽은 것인지 표시.
     * 관계로 어떻게 하는지 몰라서 join.....
     *
     * @param string $user_id
     * @return void
     */
    public function getRecommenBooksAddUserRead(string $user_id) : array
    {
        return DB::table('tbl_recommend_books_list_master')
        ->select(DB::raw("
        tbl_recommend_books_list_master.id,
            tbl_recommend_books_list_master.book_id,
            tbl_recommend_books_list_master.gubun,
            tbl_codes_master.code_name as gubun_name,
            tbl_books_master.uuid,
            tbl_books_master.title,
            tbl_books_master.authors,
            tbl_books_master.contents,
            tbl_books_master.isbn,
            tbl_books_master.publisher,
            tbl_books_master.thumbnail,
            IF(tbl_user_read_books_list.book_id, 1, 0) as read_check
        "))
        ->leftJoin('tbl_books_master', 'tbl_recommend_books_list_master.book_id', '=', 'tbl_books_master.id')
        ->leftJoin('tbl_user_read_books_list', function ($join) use ($user_id) {
            $join->on('tbl_recommend_books_list_master.book_id', '=', 'tbl_user_read_books_list.book_id')
                 ->where('tbl_user_read_books_list.user_id', '=', $user_id);
        })
        ->leftJoin('tbl_codes_master', 'tbl_recommend_books_list_master.gubun', '=', 'tbl_codes_master.code_id')
        ->get()->toArray();
    }

    /**
     * 책 상세 정보.
     *
     * @param integer $book_id
     * @return void
     */
    public function getBookInfo(int $book_id, int $user_id)
    {
        $taskResult = Books::with(['user_read' => function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        }])->where('id', $book_id)->get();
        if($taskResult->isNotEmpty()) {
			$bookInfo = $taskResult->first();
			return $bookInfo;
        }

        return false;
    }

    /**
     * 사용자 독서 활동 등록.
     *
     * @param array $params
     * @return void
     */
    public function createUserBookActivity(array $params)
    {
        $task = UserBookActivity::create([
            'book_id' => $params['book_id'],
            'user_id' => $params['user_id'],
            'uid' => $params['uid'],
            'gubun' => $params['gubun'],
            'contents' => $params['contents'],
        ]);
        if(!$task) {
			return false;
		}
        return $task->id;
    }

    /**
     * 사용자 독서 활동 리스트
     *
     * @param integer $book_id
     * @return void
     */
    public function getUserBookActivity(int $book_id, int $user_id) : array
    {
        $task = UserBookActivity::with(['user', 'gubun'])->where('book_id', $book_id)->get();

        if($task->isNotEmpty()) {
            $bookInfo = $task->toArray();

            $activitys = array_values(array_filter(array_map(function($element) use ($user_id){
                if($user_id == $element['user_id'] || $element['user']['activity_state'] == "Y") {

                    $date = Carbon::parse($element['created_at']);
                    return [
                        'activity_id' => $element['id'],
                        'user_id' => $element['user_id'],
                        'user_name' => $element['user']['name'],
                        'uid' => $element['uid'],
                        'gubun' => $element['gubun']['code_id'],
                        'gubun_name' => $element['gubun']['code_name'],
                        'contents' => $element['contents'],
                        'create_at' => $date->format('Y년 m월 d일 H시:s분'),
                    ];
                }
            }, $bookInfo)));

			return $activitys;
        }

        return [];
    }

    /**
     * 사용자 독서 활동 리스트
     *
     * @param integer $book_id
     * @return void
     */
    public function getUserBookActivity2(int $book_id, int $user_id) : array
    {
        $task = UserBookActivity::with(['user' => function ($query) use ($user_id) {
            $query->where('user_id', '<>', $user_id);
            $query->where('activity_state', 'Y');
        }, 'gubun'])->where('book_id', $book_id)->get();

        if($task->isNotEmpty()) {
			$bookInfo = $task->toArray();
			return $bookInfo;
        }

        return [];
    }

    /**
     * 책 검색.
     *
     * @param string $query
     * @return void
     */
    public function booksSearch(string $query) : array
    {
        return Books::where('title', 'like', '%' . $query . '%')
        ->orWhere('authors', 'like', '%' . $query . '%')
        ->orWhere('contents', 'like', '%' . $query . '%')
        ->orWhere('isbn', 'like', '%' . $query . '%')
        ->orWhere('publisher', 'like', '%' . $query . '%')->get()->toArray();
    }
}
