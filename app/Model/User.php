<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //
    protected $table ='user';
    public function user_bid(){
    	return $this->hasMany('App\Model\UserBid','user_id','id');
    }
    public function pay(){
    	return $this->hasMany('App\Model\Pay','user_id','id');
    }
    public function order(){
    	return $this->hasMany('App\Model\Order','user_id','id');
    }
}