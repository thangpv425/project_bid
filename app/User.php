<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname',
        'email',
        'new_email',
        'password',
        'grant',
        'status',
        'avatar',
        'ship_name',
        'ship_name_kana',
        'ship_zip',
        'ship_prefecture',
        'ship_address',
        'ship_tel',
        'post_office'
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];



}
