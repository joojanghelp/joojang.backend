<?php
namespace App\Repositories\v1;

use App\Repositories\v1\UserRepositoryInterface;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;

use App\Traits\PassportTrait;

class UserRepository implements UserRepositoryInterface
{


    public function start()
    {
    }

    public function getSettingInfo(int $user_id) : array
    {



        return [
            'state' => false,
            'message' => __('messages.error.nothing')
        ];
    }
}
