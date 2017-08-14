<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_Bid extends Model
{
    protected $table = 'user_bid';
    protected $fillable = [
    	'user_id',
    	'bid_id',
    	'bid_amount',
    	'real_bid_amount',
    	'bid_type',
    	'created_at'
    ];
}
