<?php
namespace App\Repositories\v1;

use App\Repositories\v1\BooksRepositoryInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use App\Helpers\MasterHelper;
use App\Traits\Model\BooksTrait;

class BooksRepository implements BooksRepositoryInterface
{
    use BooksTrait {
        BooksTrait::createBooks as createBooksTrait;
        BooksTrait::userBooksExits as userBooksExitsTrait;
        BooksTrait::createUserBooks as createUserBooksTrait;
        BooksTrait::getUserBooks as getUserBooksTrait;
        BooksTrait::getRecommendBooks as getRecommendBooksTrait;
        BooksTrait::createBooksRead as createBooksReadTrait;
        BooksTrait::checkBooksReads as checkBooksReadsTrait;
        BooksTrait::getRecommenBooksAddUserRead as getRecommenBooksAddUserReadTrait;
        BooksTrait::getBookInfo as getBookInfoTrait;
        BooksTrait::createUserBookActivity as createUserBookActivityTrait;
        BooksTrait::getUserBookActivity as getUserBookActivityTrait;
    }

    public function start()
    {
        echo "::: BooksRepository start :::";
    }

    /**
     * 사용자 책 등록.
     *
     * @param Request $request
     * @return array
     */
    public function attemptCreate(Request $request) : array
    {
        $User = Auth::user();

        $validator = FacadesValidator::make($request->all(), [
			'uuid' => 'required',
			'authors' => 'required',
			// 'contents' => 'required',
			'isbn' => 'required',
			'publisher' => 'required',
			// 'thumbnail' => 'required',
			'title' => 'required',
        ]);

        if( $validator->fails() ) {
            $errorMessage = "";
            foreach($validator->getMessageBag()->all() as $element):
                $errorMessage .= $element."\n";
            endforeach;
			return [
				'state' => false,
				'message' => $errorMessage
			];
        }

        $Userid = $User->id;
        $book_id = $this->createBooksTrait($request->all());

        if(!$book_id) {
            throw new \App\Exceptions\CustomException(__('message.default.error'));
        }

        $checkBook = $this->userBooksExitsTrait($Userid, $book_id);

        if($checkBook) {
            return [
				'state' => false,
				'message' => __('messages.error.exits')
			];
        }

        $create = $this->createUserBooksTrait($Userid, $book_id);

        if(!$create) {
            throw new \App\Exceptions\CustomException(__('message.default.error'));
        }

        return [
            'state' => true
        ];
    }

    /**
     * 사용자가 등록한 책 목록.
     *
     * @return void
     */
    public function getBooksList()
    {
        $returnData = [];

        $User = Auth::user();

        $Userid = $User->id;

        $task = $this->getUserBooksTrait($User->id);

        foreach($task as $element):
            $user_books_number = $element['id'];
            $book_id = $element['books']['id'];
            $uuid = $element['books']['uuid'];

            $returnData[] = [
                    'list_id' => $element['id'],
                    'book_id' => $element['books']['id'],
                    'uuid' => $element['books']['uuid'],
                    'title' => $element['books']['title'],
                    'authors' => $element['books']['authors'],
                    'contents' => $element['books']['contents'],
                    'isbn' => $element['books']['isbn'],
                    'publisher' => $element['books']['publisher'],
                    'thumbnail' => $element['books']['thumbnail'],
            ];
        endforeach;

        if(empty($returnData)) {
            return [
                'state' => false,
                'message' => __('messages.error.nothing')
            ];
        }

        return [
            'state' => true,
            'data' => $returnData
        ];
    }

    /**
     * 추천 도서 목록중 사용자가 읽은 책 표시.
     *
     * @return void
     */
    public function setRecommendBooks()
    {
        $User = Auth::user();

        $Userid = (isset($User->id) && $User->id) ? $User->id : 0;

        $task = array_map(function($element) {

            return [
                'list_id' => $element['id'],
                'gubun' => $element['gubun'],
                'gubun_name' => $element['gubun_name'],
                'book_id' => $element['book_id'],
                'uuid' => $element['uuid'],
                'title' => $element['title'],
                'authors' => $element['authors'],
                'contents' => $element['contents'],
                'isbn' => $element['isbn'],
                'publisher' => $element['publisher'],
                'thumbnail' => $element['thumbnail'],
                'read_check' => ($element['read_check'] == 1) ? true: false,
            ];
        }, json_decode(json_encode($this->getRecommenBooksAddUserReadTrait($Userid)), true));

        if(!$task) {
            return [
                'state' => false,
                'message' => __('messages.error.nothing')
            ];
        }
        return [
            'state' => true,
            'data' => $task
        ];
    }

    /**
     * 사용자가 읽은책 표시.
     *
     * @param Request $request
     * @return void
     */
    public function setRecommendRead(Request $request)
    {
        $User = Auth::user();

        $validator = FacadesValidator::make($request->all(), [
			'book_id' => 'required',
        ]);

        if( $validator->fails() ) {
            $errorMessage = "";
            foreach($validator->getMessageBag()->all() as $element):
                $errorMessage .= $element."\n";
            endforeach;
			return [
				'state' => false,
				'message' => $errorMessage
			];
        }

        if($this->checkBooksReadsTrait($User->id, $request->input('book_id'))) {
            return [
                'state' => false,
                'message' => __('messages.error.exits')
            ];
        }

        $task = $this->createBooksReadTrait($User->id, $request->input('book_id'));

        if(!$task) {
            return [
                'state' => false,
                'message' => __('messages.default.error')
            ];
        }

        return [
            'state' => true
        ];
    }

    /**
     * 책 상세 정보.
     *
     * @param integer $book_id
     * @return array
     */
    public function getBookInfo(int $book_id) : array
    {
        $task = $this->getBookInfoTrait($book_id);

        if(!$task) {
            return [
                'state' => false,
                'message' => __('messages.error.nothing')
            ];
        }

        $activityTask = $this->getUserBookActivityTrait($book_id);

        $activitys = array_map(function($element) {
            return [
                'activity_id' => $element['id'],
                'user_id' => $element['user_id'],
                'user_name' => $element['user']['name'],
                'uid' => $element['uid'],
                'gubun' => $element['gubun']['code_id'],
                'gubun_name' => $element['gubun']['code_name'],
                'contents' => $element['contents'],
            ];
        }, $this->getUserBookActivityTrait($book_id));

        return [
            'state' => true,
            'data' => [
                'uuid' => $task->uuid,
                'user_id' => $task->user_id,
                'user_name' => null,
                'title' => $task->title,
                'authors' => $task->authors,
                'contents' => $task->contents,
                'isbn' => $task->isbn,
                'publisher' => $task->publisher,
                'thumbnail' => $task->thumbnail,
                'book_activity' => $activitys
            ]
        ];
    }

    /**
     * 독서 활동 쓰기.
     *
     * @param Request $request
     * @return void
     */
    public function attemptCreateActivity(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'book_id' => 'required',
            'gubun' => 'required',
            'contents' => 'required',
        ]);

        if( $validator->fails() ) {
            $errorMessage = "";
            foreach($validator->getMessageBag()->all() as $element):
                $errorMessage .= $element."\n";
            endforeach;
			return [
				'state' => false,
				'message' => $errorMessage
			];
        }

        $createObject = [
            'book_id' => $request->input('book_id'),
            'user_id' => Auth::id(),
            'uid' => MasterHelper::GenerateUUID(),
            'gubun' => $request->input('gubun'),
            'contents' => $request->input('contents'),
        ];

        $task = $this->createUserBookActivityTrait($createObject);

        if(!$task) {
            return [
                'state' => false,
                'message' => __('messages.default.error')
            ];
        }

        return [
            'state' => true,
            'message' => __('messages.default.do_success')
        ];
    }
}
