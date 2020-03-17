<?php

namespace App\Model\Book;

use App\Model\BaseModel;

class Books extends BaseModel
{
    protected $table = "tbl_books_master";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'user_id', 'title', 'authors', 'contents', 'isbn', 'publisher', 'thumbnail', 'active'
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

    public function users_book()
    {
        return $this->hasOne('App\Model\Book\UsersBooks', 'id', 'book_id');
    }
}
