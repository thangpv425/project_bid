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
    protected $table ='category_product';
    public function category(){
    	return $this->belongsTo('App\Model\Category','category_id','id');
    }
    public function product(){
    	return $this->belongsTo('App\Model\Product','product_id','id');
    }
}