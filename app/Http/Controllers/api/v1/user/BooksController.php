<?php

namespace App\Http\Controllers\api\v1\user;

use App\Http\Controllers\api\BaseController;
use Illuminate\Http\Request;

use App\Repositories\v1\BooksRepository;

class BooksController extends BaseController
{
    protected $books;

    public function __construct(BooksRepository $books)
    {
        $this->books = $books;
    }

    /**
     * 책 등록.
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $task = $this->books->attemptCreate($request);

        if($task['state']) {
            return BaseController::defaultSuccessCreateResponse();
        } else {
            return BaseController::defaultCreateFailResponse($task['message']);
        }
    }

    /**
     * 자기가 등록한 책 리스트
     *
     * @return void
     */
    public function index()
    {
        $task = $this->books->getBooksList();
        if($task['state'] == false) {
            return BaseController::defaultListNothingResponse($task['message']);
        } else {
            return BaseController::defaultListSuccessResponse($task['data']);
        }
    }

    /**
     * 사용자 등록 책 리스트 페이징 타입.
     *
     * @param integer $page
     * @return void
     */
    public function index_page_type(int $page)
    {
        $task = $this->books->getBooksListPageType($page);
        if($task['state'] == false) {
            return BaseController::defaultListNothingResponse($task['message']);
        } else {
            return BaseController::defaultPageListSuccessResponse($task['data']);
        }
    }

    /**
     * 추천 목록에서 자기가 읽은 책 표시.
     *
     * @param Request $request
     * @return void
     */
    public function recommend_read(Request $request) {
        $task = $this->books->setRecommendRead($request);

        if($task['state']) {
            return BaseController::defaultSuccessCreateResponse();
        } else {
            return BaseController::defaultCreateFailResponse($task['message']);
        }
    }

    /**
     * 책 읽음 표시 삭제.
     *
     * @param Request $request
     * @return void
     */
    public function recommend_read_delete(Request $request) {
        $task = $this->books->setRecommendReadDelete($request);

        if($task['state']) {
            return BaseController::defaultDeleteSuccessResponse();
        } else {
            return BaseController::defaultCreateFailResponse($task['message']);
        }
    }

    public function update()
    {
        return __FUNCTION__;
    }

    public function delete()
    {
        return __FUNCTION__;
    }

    /**
     * 독서 활동 등록.
     *
     * @param Request $request
     * @return void
     */
    public function create_activity(Request $request)
    {
        $task = $this->books->attemptCreateActivity($request);

        if($task['state']) {
            return BaseController::defaultSuccessCreateResponse();
        } else {
            return BaseController::defaultCreateFailResponse($task['message']);
        }
    }

    /**
     * 독서 활동 삭제.
     *
     * @param Request $request
     * @return void
     */
    public function delete_activity(Request $request)
    {
        $task = $this->books->delete_activity($request);
        if($task['state']) {
            return BaseController::defaultDeleteSuccessResponse();
        } else {
            return BaseController::defaultCreateFailResponse($task['message']);
        }
    }
}
