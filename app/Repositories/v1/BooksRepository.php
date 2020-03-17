<?php
namespace App\Repositories\v1;

use App\Repositories\v1\BooksRepositoryInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;

use App\Traits\Model\BooksTrait;


class BooksRepository implements BooksRepositoryInterface
{
    use BooksTrait {
        BooksTrait::createBooks as createBooksTrait;
        BooksTrait::userBooksExits as userBooksExitsTrait;
        BooksTrait::createUserBooks as createUserBooksTrait;
        BooksTrait::getUserBooks as getUserBooksTrait;
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
			'contents' => 'required',
			'isbn' => 'required',
			'publisher' => 'required',
			'thumbnail' => 'required',
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

    public function getBooksList()
    {
        $User = Auth::user();

        $Userid = $User->id;

        $task = $this->getUserBooksTrait($User->id);

        print_r($task);



    }
}
