<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    //
    protected $table ='product';
    public function categoryproduct(){
    	return $this->hasMany('App\Model\CategoryProduct','product_id','id');
    }
    public function bid(){
    	return $this->hasMany('App\Model\UserBid','product_id','id');
    }
}