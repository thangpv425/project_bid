<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    
    protected $table ='order';
    public function user(){
    	return $this->belongsTo('App\Model\Product','user_id','id');
    }
    public function bid(){
    	return $this->belongsTo('App\Model\Bid','bid_id','id');
    }
    public function pay(){
    	return $this->belongsTo('App\Model\Pay','pay_id','id');
    }
}