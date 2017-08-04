<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    //
    protected $table ='bid';
    public function product(){
    	return $this->belongsTo('App\Model\Product','product_id','id');
    }
    public function user_bid(){
    	return $this->hasMany('App\Model\UserBid','user_id_got','id');
    }
    public function pay(){
    	return $this->belongsTo('App\Model\Pay','bid_id','id');
    }
    public function order(){
    	return $this->belongsTo('App\Model\Order','bid_id','id');
    }
}