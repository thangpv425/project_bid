<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    //
    protected $fillable = [
    	'product_id',
    	'cost_begin',
    	'cost_sell',
    	'current_highest_price',
    	'current_highest_bidder_id',
    	'time_begin',
    	'time_end',
    	'status',
    	'current_price',
    	'current_highest_bidder_name'
    ];
}
