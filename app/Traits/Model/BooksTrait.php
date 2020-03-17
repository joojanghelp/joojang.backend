<?php
namespace App\Traits\Model;

use App\User;
use App\Model\Book\UsersBooks;
use App\Model\Book\Books;

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

    public function getUserBooks(int $user_id)
    {
        return UsersBooks::with('books')->where('user_id', $user_id)->get()->toArray();
    }
}
