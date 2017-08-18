<?php
namespace App\Repositories\Bid;

use App\Models\Bid;
use Carbon\Carbon;
use Illuminate\Container\Container as App;
use App\Repositories\BaseRepository;

class BidRepository extends BaseRepository implements BidRepositoryInterface {
    public function __construct(Bid $bid) {
        parent::__construct($bid);
    }

    public function getBid($id){
    	$bid = Bid::where(function ($query) use ($id) {
            $query->where('id','=',$id)
                ->where('time_begin', '<=', Carbon::now())
                ->where('time_end', '>=', Carbon::now());
        })->first();
        return $bid? $bid:null;
    }

}
