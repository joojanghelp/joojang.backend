<?php
namespace App\Repositories;

use App\Repositories\TestRepositoryInterface;

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
