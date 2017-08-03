<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    //
    protected $table ='categories';
    public function category_product(){
    	return $this->hasMany('App\Model\CategoryProduct','category_id','id');
    }
    
}