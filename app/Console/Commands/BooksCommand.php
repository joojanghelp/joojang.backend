<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use App\Model\Book\UsersBooks;
use App\Model\Book\Books;
use App\Model\Codes;
use App\Model\Book\RecommendBooks;

class BooksCommand extends Command
{
    protected $init_code = [];
    protected $init_book = [];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:manager {options : manager options}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '추천 도서 임시 데이터 등록.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $option = $this->argument('options');

        if($option == "test_recommand") {
            $this->test_recommand_init();
        } else if($option == "books_user_id"){
            $this->books_user_id_init();
        }

        return true;
    }

    public function books_user_id_init()
    {
        $task = UsersBooks::all();

        $task->each(function($result){
            $task = $result->toArray();

            $book_id = $task['id'];
            $user_id = $task['user_id'];

            Books::find($book_id)->update(['user_id' => $user_id]);

        });
    }


    public function test_recommand_init()
    {
        $this->initCategory();
        $this->initBooks();
        $this->insertRecommend();
    }

    /**
     * 카테고리
     */
    public function initCategory()
    {
        $init_code = [];

        $task = Codes::where('group_id', 'B11')->whereNotNull('code_id')->get()->toArray();

        foreach ($task as $element) :
            $code_id = $element['code_id'];
            $code_name = $element['code_name'];

            $init_code[] = $code_id;
        endforeach;
        $this->init_code = $init_code;
    }

    /**
     * 등록할 책
     *
     * @return void
     */
    public function initBooks()
    {
        $task = Books::where('active', 'Y')->whereNotNull('contents')->get()->toArray();

        foreach ($task as $element) :
            $book_id = $element['id'];
            $this->init_book[] = $book_id;
        endforeach;
    }

    /**
     * 추천 도서 등록.
     *
     * @return void
     */
    public function insertRecommend()
    {
        RecommendBooks::truncate();
        shuffle($this->init_book);

        foreach ($this->init_book as $element) :
            shuffle($this->init_code);
            $category = $this->init_code[0];
            RecommendBooks::create([
                'user_id' => 1,
                'book_id' => $element,
                'gubun' => $category
            ]);
        endforeach;
    }
}
