<?php

namespace App\Model\Book;

use App\Model\BaseModel;

class UsersBooks extends BaseModel
{
    protected $table = "tbl_users_books_list_master";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'book_id', 'active'
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

    public function books()
    {
        return $this->hasOne('App\Model\Book\Books', 'id', 'book_id');
    }
}
