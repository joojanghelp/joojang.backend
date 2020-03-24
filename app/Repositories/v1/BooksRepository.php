<?php
namespace App\Repositories\v1;

use App\Repositories\v1\BooksRepositoryInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use App\Helpers\MasterHelper;
use App\Traits\Model\BooksTrait;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BooksRepository implements BooksRepositoryInterface
{
    protected $pageRow = 30;

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
        BooksTrait::booksSearch as booksSearchTrait;
    }

    public function start()
    {
        echo "::: BooksRepository start :::";
    }

    //TODO: 페이징 처리를 어디 에 옮겨야 함.

    /**
     * 페이징 함수 키값 있음.
     *
     * @param [type] $items
     * @param integer $perPage
     * @param [type] $page
     * @param [type] $baseUrl
     * @param array $options
     * @return void
     */
    public function paginate($items, $perPage = 15, $page = null, $baseUrl = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        $lap = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

        if ($baseUrl) {
            $lap->setPath($baseUrl);
        }

        return $lap;
    }

    /**
     * 페이징 함수. key 값업음.
     *
     * @param [type] $items
     * @param integer $perPage
     * @param [type] $page
     * @param array $options
     * @return void
     */
    public function paginateCollection($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
        return new \Illuminate\Pagination\LengthAwarePaginator(array_values($items->forPage($page, $perPage)->toArray()), $items->count(), $perPage, $page, $options);
        //ref for array_values() fix: https://stackoverflow.com/a/38712699/3553367
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

        $user_id = Auth::id();
        $createObject = $request->all();
        $createObject['user_id'] = $user_id;
        $book_id = $this->createBooksTrait($createObject);

        if(!$book_id) {
            throw new \App\Exceptions\CustomException(__('message.default.error'));
        }

        $checkBook = $this->userBooksExitsTrait($user_id, $book_id);

        if($checkBook) {
            return [
				'state' => false,
				'message' => __('messages.error.exits')
			];
        }

        $create = $this->createUserBooksTrait($user_id, $book_id);

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
     * 사용자 등록 책 리스트 페이징 타입.
     *
     * @param integer $page
     * @return void
     */
    public function getBooksListPageType(int $page)
    {
        $returnData = [];
        $user_id = Auth::id();

        $task = array_map(function($element) {
            return [
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
        },  $this->getUserBooksTrait($user_id));

        if(!$task) {
            return [
                'state' => false,
                'message' => __('messages.error.nothing')
            ];
        }

        $taskResult = $this->paginateCollection($task, $this->pageRow, $page)->toArray();

        if($taskResult) {
            $taskResult['items'] = $taskResult['data'];
            unset($taskResult['data']);
        }

        return [
            'state' => true,
            'data' => $taskResult
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
     * 추천 도서 페이징 타입.
     *
     * @param integer $page
     * @return void
     */
    public function setRecommendBooksPageType(int $page)
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

        $taskResult = $this->paginateCollection($task, $this->pageRow, $page)->toArray();

        if($taskResult) {
            $taskResult['items'] = $taskResult['data'];
            unset($taskResult['data']);
        }

        return [
            'state' => true,
            'data' => $taskResult
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
        $user_id = Auth::id();
        $task = $this->getBookInfoTrait($book_id, $user_id);

        if(!$task) {
            return [
                'state' => false,
                'message' => __('messages.error.nothing')
            ];
        }

        $activitys = $this->getUserBookActivityTrait($book_id, $user_id);

        return [
            'state' => true,
            'data' => [
                'uuid' => $task->uuid,
                'user_id' => $task->user_id,
                'user_name' => $task->user->name,
                'title' => $task->title,
                'authors' => $task->authors,
                'contents' => $task->contents,
                'isbn' => $task->isbn,
                'publisher' => $task->publisher,
                'thumbnail' => $task->thumbnail,
                'read_check' => (empty($task->user_read)) ? false: true,
                'book_activity' => (empty($activitys)) ? null : $activitys
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

        $bookExits = $this->getBookInfoTrait($request->input('book_id'), Auth::id());
        if(!$bookExits) {
            return [
				'state' => false,
				'message' => __('messages.error.book_nothing')
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

    /**
     * 책 검색 리스트
     *
     * @param Request $request
     * @return void
     */
    public function attemptBookSearch(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'query' => 'required',
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

        $searchQuery = $request->input('query');

        $activitys = array_map(function($element) {
            return [
                'book_id' => $element['id'],
                'uuid' => $element['uuid'],
                'title' => $element['title'],
                'authors' => $element['authors'],
                'contents' => $element['contents'],
                'isbn' => $element['isbn'],
                'publisher' => $element['publisher'],
                'thumbnail' => $element['thumbnail'],
            ];
        }, $this->booksSearchTrait($searchQuery));

        if(empty($activitys)) {
            return [
                'state' => false,
                'message' => __('messages.error.nothing')
            ];
        }

        return [
            'state' => true,
            'data' => $activitys
        ];
    }
}
