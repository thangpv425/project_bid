<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class BidTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for($i=1; $i<11; $i++ ){
        	DB::table('bids')->insert([
        		'product_id'=>$i,
        		'cost_begin'=>1000,
                'current_price'=>0,
                'current_highest_price'=>0,
        		'cost_sell'=>10000,
        		'current_highest_bidder_id' => 1,
        		'time_begin'=>'2017-08-11 06:43:43',
        		'time_end'=>'2017-08-12 06:43:43',
        		'status'=>2,
        	]);
        }
    }
}
