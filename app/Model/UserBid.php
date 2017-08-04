<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAuction extends Model
{
    
    protected $table ='user_auction';
    public function bid(){
    	return $this->belongsTo('App\Model\Bid','bid_id','id');
    }
    public function user(){
    	return $this->belongsTo('App\Model\User','user_id','id');
    }
}