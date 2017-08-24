<?php
namespace App\Repositories\User_Bid;

use App\Models\User_Bid;
use App\Models\UserBid;
use Carbon\Carbon;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use App\Repositories\User_Bid\UserBidRepositoryInterface;

class UserBidRepository extends BaseRepository implements UserBidRepositoryInterface {
    public function __construct(User_Bid $user_bid) {
        parent::__construct($user_bid);
    }

    public function getBidHistory($bidId) {
        $rows = UserBid::join('users', 'user_bid.user_id', '=', 'users.id')
            ->select(DB::raw('user_bid.*, users.nickname as nickname'))
            ->where('bid_id', '=', $bidId)
            ->orderBy('id', 'desc')
            ->get();
        return $rows;
    }
}
