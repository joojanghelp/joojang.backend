<?php
namespace App\Repositories\v1;

use App\Repositories\v1\TestRepositoryInterface;

class TestRepository implements TestRepositoryInterface
{
    public function start() {
        echo "start";
    }

    public function test()
    {
        echo "::test::";
    }
}
