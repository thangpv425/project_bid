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

    /**
     * Get current bids
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getCurrentBids() {
        $now = Carbon::now();
        $bids = Bid::where('time_begin', '<', $now)
            ->where('time_end', '>', $now)
            ->paginate(Config::get('constants.number_item_per_page.current_bids'));
        return $bids;
    }

    /**
     * Get success bids
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getSuccessBids() {
        $now = Carbon::now();
        $bids = Bid::where('time_begin', '<', $now)
            ->where('time_end', '<', $now)
            ->where('current_highest_bidder_id', '!=', null)
            ->latest('time_begin')
            ->paginate(Config::get('constants.number_item_per_page.success_bids'));
        return $bids;
    }

    /**
     * Get joining bids
     * @param $userId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getJoiningBids($userId) {
        $now = Carbon::now();
        $bids = Bid::join('user_bid', 'bids.id', '=','user_bid.bid_id')
            ->select('bids.*')
            ->where('bids.time_end', '>', $now)
            ->where('bids.time_begin', '<', $now)
            ->where('user_bid.user_id', '=', $userId)
            ->distinct()
            ->paginate(Config::get('constants.number_item_per_page.success_bids'));
        return $bids;
    }

    public function getFailBids($userId) {
        $now = Carbon::now();
        $bids = Bid::join('user_bid', 'bids.id', '=','user_bid.bid_id')
            ->select('bids.*')
            ->where('bids.time_end', '<', $now)
            ->where('bids.time_begin', '<', $now)
            ->where('user_bid.user_id', '=', $userId)
            ->where('bids.current_highest_bidder_id', '!=', $userId)
            ->distinct()
            ->paginate(Config::get('constants.number_item_per_page.fail-bid'));
        return $bids;
    }

    public function getUnpaidBids($userId) {
        $now = Carbon::now();
        $bids = Bid::where('current_highest_bidder_id', '=', $userId)
            ->where(function($query) {
                $query->where('status', '=', Config::get('constants.bid_status.pending_payment'))
                    ->orWhere('status', '=', Config::get('constants.bid_status.waiting_payment_confirm'));
            })
            ->where('time_end' , '<', $now)
            ->where('time_begin', '<', $now)
            ->distinct()
            ->paginate(Config::get('constants.number_item_per_page.fail-bid'));
        return $bids;
    }

    public function getPaidBids($userId) {
        $now = Carbon::now();
        $bids = Bid::where('current_highest_bidder_id', '=', $userId)
            ->where('status', '=', Config::get('constants.bid_status.payment_confirm_success'))
            ->where('time_end' , '<', $now)
            ->where('time_begin', '<', $now)
            ->distinct()
            ->paginate(Config::get('constants.number_item_per_page.fail-bid'));
        return $bids;
    }

    public function getCanceledBids($userId) {
        $now = Carbon::now();
        $bids = Bid::where('current_highest_bidder_id', '=', $userId)
            ->where('status', '=', Config::get('constants.bid_status.cancel'))
            ->where('time_end' , '<', $now)
            ->where('time_begin', '<', $now)
            ->distinct()
            ->paginate(Config::get('constants.number_item_per_page.fail-bid'));
        return $bids;
    }
}
