<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\api\v1\BaseController;

use App\Repositories\TestRepository;

class TestController extends BaseController
{
    protected $test;

    public function __construct(TestRepository $test)
    {
        $this->test = $test;

    }

    public function test()
    {
        $this->test->test();
    }
}
