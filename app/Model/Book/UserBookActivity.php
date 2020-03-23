<?php

namespace App\Model\Book;

use Illuminate\Database\Eloquent\Model;

class UserBookActivity extends Model
{
    protected $table = "tbl_user_books_activity_master";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'book_id', 'user_id', 'uid', 'gubun', 'contents'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];

    /**
     * 구분 콛.
     *
     * @return void
     */
    public function gubun()
    {
        return $this->hasOne('App\Model\Codes', 'code_id', 'gubun');
    }

    /**
     * 사용자 정보.
     *
     * @return void
     */
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
