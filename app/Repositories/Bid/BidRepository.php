<?php
namespace App\Repositories\Bid;

use App\Models\Bid;
use Carbon\Carbon;
use Illuminate\Container\Container as App;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Config;

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
        return (!empty($bid))? $bid:null;
    }

    public function getCurrentBids() {
        $now = Carbon::now();
        $bids = Bid::where('time_begin', '<', $now)
            ->where('time_end', '>', $now)
            ->paginate(Config::get('constants.number_item_per_page.success_bids'));
        return $bids;
    }

    public function getSuccessBids() {
        $now = Carbon::now();
        $bids = Bid::where('time_begin', '<', $now)
            ->where('time_end', '<', $now)
            ->where('current_highest_bidder_id', '!=', null)
            ->latest('time_begin')
            ->paginate(Config::get('constants.number_item_per_page.current_bids'));
        return $bids;
    }


}
