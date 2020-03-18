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
    protected $signature = 'books:test_recommand';

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
        $this->init();

        return true;
    }


    public function init()
    {
        $this->initCategory();
        $this->initBooks();
        $this->insertRecommend();
    }

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

    public function initBooks()
    {
        $task = Books::where('active', 'Y')->whereNotNull('contents')->get()->toArray();

        foreach ($task as $element) :
            $book_id = $element['id'];
            $this->init_book[] = $book_id;
        endforeach;
    }

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
