<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EmailAuth extends Model
{
    protected $table = "tbl_email_auth_master";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_uuid', 'auth_code', 'verified_at'
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


    public function user()
    {
        return $this->belongsTo('App\User' , 'user_uuid', 'uuid');
    }
}
