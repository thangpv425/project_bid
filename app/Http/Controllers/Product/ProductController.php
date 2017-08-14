<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\User_Bid;
use DB;
use App\Repositories\Bid\BidRepositoryInterface;
use App\Repositories\User_Bid\UserBidRepositoryInterface;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
class ProductController extends Controller
{
	protected $bidRepository;
    protected $user_bidRepository;
    protected $time_now;
    protected $bid_amount_step;
    protected $bid_type_manual;
    protected $bid_type_auto;
	public function __construct(BidRepositoryInterface $bidRepository,UserBidRepositoryInterface $user_bidRepository) {
		$this->bidRepository = $bidRepository;
        $this->user_bidRepository = $user_bidRepository;
        $this->time_now = Carbon::now();
        $this->bid_amount_step = Config::get('constants.bid_amount_step');
        $this->bid_type_manual = Config::get('constants.bid_amount_type.manual');
        $this->bid_type_auto = Config::get('constants.bid_amount_type.auto');
	}

    /**
     * @param $id
     * @return Bid
     */
    public function getBid($id) {
        $bid = $this->bidRepository->find($id);
        return view('bid.bid_current',['bid'=>$bid]);
    }

    /**
     * @param $request{user_id, real_bid_amound}
     * @return $current_price
     */
    public function postBid(Request $request, $id) {
        $bid = $this->bidRepository->find($id);
        
        if($bid->status == Config::get('constants.bid_status.begining')){
            if($request->real_bid_amount <=  ($bid->current_price)){
                return false;
            }elseif ($request->real_bid_amount >= $bid->cost_sell) {
                $this->amountCostSell($bid, $request, $id);
            }else{
                if($request->real_bid_amount < $bid->current_highest_price){
                    $this->amountLessHightest($bid, $request, $id);
                }elseif($request->real_bid_amount == $bid->current_highest_price){
                    $this->amountEqualHightest($bid, $request, $id);
                }else{
                    $this->amountBetterHightest($bid, $request, $id);
                }
            }
            
        }elseif ($bid->status == Config::get('constants.bid_status.no_user_bid')) {

            $this->amountNoUserBid($bid, $request, $id);
        }else
            return false;
        $respont = array(
                'current_highest_bidder_id'=> $bid->current_highest_bidder_id,
                'current_highest_bidder_name'=>$bid->current_highest_bidder_name,
                'current_price'=> $bid->current_price,
            );
        return $respont;  
    }

    /**
     * update bid in case no user bid
     * @param $bid, $request, $id
     * @return void
     */
    public function amountNoUserBid($bid, $request, $id){

        try {
            $bid_session_user_current = array(
                'user_id' => $request->user_id,
                'bid_id' => $id,
                'real_bid_amount' => $request->real_bid_amount,
                'bid_amount' => $request->real_bid_amount,
                'bid_type' => $this->bid_type_manual,
                'created_at'=> $this->time_now
            );

            DB::beginTransaction();
            //$this->user_bidRepository->create($bid_session_user_current);
            $bid->update([
                'current_price' => $request->real_bid_amount,
                'current_highest_price' => $request->real_bid_amount,
                'current_highest_bidder_id' => $request->user_id,
                'current_highest_bidder_name' => $request->user_name,
                'status' => Config::get('constants.bid_status.begining'),
            ]);
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * update bid in case amount input is less than current hightest 
     * @param $bid, $request, $id
     * @return void
     */
    public function amountLessHightest($bid, $request, $id){
        try {
            $bid_session_user_current = array(
                'user_id' => $request->user_id,
                'bid_id' => $id,
                'real_bid_amount' => $request->real_bid_amount,
                'bid_amount' => $request->real_bid_amount,
                'bid_type' => $this->bid_type_manual,
                'created_at'=> $this->time_now
            );

            $bid_session_user_highest = array(
                'user_id' => $bid->current_highest_bidder_id,
                'bid_id' => $id,
                'real_bid_amount' => $bid->current_highest_price,
                'bid_amount' => $bid->current_price+$this->bid_amount_step,  
                'bid_type' => $this->bid_type_auto,
                'created_at'=> $this->time_now
            );

            DB::beginTransaction();
            $this->user_bidRepository->multiCreate([$bid_session_user_highest,$bid_session_user_current]);
            $bid->update(['current_price' => $request->real_bid_amount+$this->bid_amount_step]);
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * update bid in case amount input is equal current hightest 
     * @param $bid, $request, $id
     * @return void
     */
    public function amountEqualHightest($bid, $request, $id){
        try {
            $bid_session_user_current = array(
                'user_id' => $request->user_id,
                'bid_id' => $id,
                'real_bid_amount' => $request->real_bid_amount,
                'bid_amount' => $bid->current_price+$this->bid_amount_step,
                'bid_type' => $this->bid_type_manual,
                'created_at'=> $this->time_now
            );

            $bid_session_user_highest = array(
                'user_id' => $bid->current_highest_bidder_id,
                'bid_id' => $id,
                'real_bid_amount' => $bid->current_highest_price,
                'bid_amount' => $bid->current_highest_price,  
                'bid_type' => $this->bid_type_auto,
                'created_at'=> $this->time_now
            );

            DB::beginTransaction();
            $this->user_bidRepository->multiCreate([$bid_session_user_highest,$bid_session_user_current]);
            $bid->update([
                'current_price' => $request->real_bid_amount,
                'current_highest_price' => $request->real_bid_amount,
                'current_highest_bidder_id' => $request->user_id,
                'current_highest_bidder_name' => $request->user_name,
            ]);
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }


    /**
     * update bid in case amount input is more than current hightest 
     * @param $bid, $request, $id
     * @return void
     */
    public function amountBetterHightest($bid, $request, $id){
        try {
            $bid_session_user_current = array(
                'user_id' => $request->user_id,
                'bid_id' => $id,
                'real_bid_amount' => $request->real_bid_amount,
                'bid_amount' => $request->real_bid_amount,
                'bid_type' => $this->bid_type_manual,
                'created_at'=> $this->time_now
            );

            $bid_session_user_highest = array(
                'user_id' => $bid->current_highest_bidder_id,
                'bid_id' => $id,
                'real_bid_amount' => $bid->current_highest_price,
                'bid_amount' => $bid->current_highest_price,  
                'bid_type' => $this->bid_type_auto,
                'created_at'=> $this->time_now
            );

            DB::beginTransaction();
            $this->user_bidRepository->multiCreate([$bid_session_user_highest,$bid_session_user_current]);
            $bid->update([
                'current_price' => $bid->current_highest_price+$this->bid_amount_step,
                'current_highest_price' => $request->real_bid_amount,
                'current_highest_bidder_id' => $request->user_id,
                'current_highest_bidder_name' => $request->user_name,
            ]);
            DB::commit();
        } catch (Exception $e) {
            B::rollback();
            return false;
        }
    }

    /**
     * update bid in case amount input is more than cost sell
     * @param $bid, $request, $id
     * @return void
     */
    public function amountCostSell($bid, $request, $id){
        try {
            $bid_session_user_current = array(
                'user_id' => $request->user_id,
                'bid_id' => $id,
                'real_bid_amount' => $request->real_bid_amount,
                'bid_amount' => $request->real_bid_amount,
                'bid_type' => $this->bid_type_manual,
                'created_at'=> $this->time_now
            );
            
            DB::beginTransaction();
            $this->user_bidRepository->create($bid_session_user_current);
            $bid->update([
                'current_price' => $bid->cost_sell,
                'current_highest_price' => $request->real_bid_amount,
                'current_highest_bidder_id' => $request->user_id,
                'current_highest_bidder_name' => $request->user_name,
                'status' => Config::get('constants.bid_status.ended'),
                'time_end' => $this->time_now
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }
}

