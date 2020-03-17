<?php
namespace App\Repositories\v1;

use App\Repositories\v1\BooksRepositoryInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;

use App\Traits\Model\BooksTrait;


class BooksRepository implements BooksRepositoryInterface
{
    use BooksTrait ;

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


        return [];
    }
}
