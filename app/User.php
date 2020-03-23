<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'uuid', 'type', 'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * 독서 활동.
     *
     * @return void
     */
    public function activity()
    {
        return $this->hasMany('App\Model\Book\UserBookActivity', 'user_id', 'id');
    }

    /**
     * 읽은 책 리스트.
     *
     * @return void
     */
    public function read_book()
    {
        return $this->hasMany('App\Model\Book\UserReadBooks', 'user_id', 'id');
    }

    /**
     * 관계 사용자 타입.
     *
     * @return void
     */
    public function type()
    {
        return $this->hasOne('App\Model\Codes', 'code_id', 'type');
    }

    /**
     * 사용자 상태...
     *
     * @return void
     */
    public function state()
    {
        return $this->hasOne('App\Model\Codes', 'code_id', 'state');
    }

    /**
     * 사용자 레벨.
     *
     * @return void
     */
    public function level()
    {
        return $this->hasOne('App\Model\Codes', 'code_id', 'level');
    }
}
