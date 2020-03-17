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


    public function create(Request $request)
    {
        $task = $this->books->attemptCreate($request);

        if($task['state']) {
            return BaseController::defaultSuccessCreateResponse();
        } else {
            return BaseController::defaultCreateFailResponse($task['message']);
        }
    }

    public function index()
    {
        $task = $this->books->getBooksList();
        if($task['state'] == false) {
            return BaseController::defaultListNothingResponse($task['message']);
        } else {
            return BaseController::defaultListSuccessResponse($task['data']);
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
}
