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
              
        if($bid->time_begin <= $this->time_now && $this->time_now <= $bid->time_end && $bid->status == Config::get('constants.bid_status.begining')){
            //return $bid->current_highest_bidder_id == null ? 1:0;
            if($request->real_bid_amount <  ($bid->current_price+$this->bid_amount_step)){
                $respont = array('error'=>'bid must more than current proce');
            }elseif ($bid->current_highest_bidder_id == null) {
                //return "nguoi tra gia lan dau";
                $this->amountFirst($bid, $request);
            }elseif ($request->real_bid_amount >= $bid->cost_sell) {
                //return " tra gia ban luon";
                $this->amountCostSell($bid, $request);
            }else{
                if($request->real_bid_amount < $bid->current_highest_price){
                    //return "tra gia nho hơn current hightest";
                    $this->amountLessHightest($bid, $request);
                }elseif($request->real_bid_amount == $bid->current_highest_price){
                    //return  "tra gia bang current hightest";
                    $this->amountEqualHightest($bid, $request);
                }else{
                    //return "tra gia lon hon gia current hightest";
                    $this->amountBetterHightest($bid, $request);
                }
            }
            $respont = array(
                'success'=>'bid success',
                'current_highest_bidder_id'=> $bid->current_highest_bidder_id,
                'current_highest_bidder_name'=>$bid->current_highest_bidder_name,
                'current_price'=> $bid->current_price,
            );
        }else
            $respont = array('error'=>'bid is invalid');
        
        return $respont;  
    }

    /**
     * update bid in case nguoi tra gia lan dau
     * @param $bid, $request
     * @return void
     */
    public function amountFirst($bid, $request){
        try {
            if($request->real_bid_amount < $bid->cost_sell){
                $bid_session_user_current = array(
                    'user_id' => $request->user_id,
                    'bid_id' => $bid->id,
                    'real_bid_amount' => $request->real_bid_amount,
                    'bid_amount' => $bid->cost_begin,
                    'bid_type' => $this->bid_type_manual,
                    'created_at'=> $this->time_now
                );
                $bid_update = array(
                    'current_price' => $bid->cost_begin,
                    'current_highest_price' => $request->real_bid_amount,
                    'current_highest_bidder_id' => $request->user_id,
                    'current_highest_bidder_name' => $request->user_name,
                );
            }else{
                $bid_session_user_current = array(
                    'user_id' => $request->user_id,
                    'bid_id' => $bid->id,
                    'real_bid_amount' => $bid->cost_sell,
                    'bid_amount' => $bid->cost_sell,
                    'bid_type' => $this->bid_type_manual,
                    'created_at'=> $this->time_now
                );
                $bid_update = array(
                    'current_price' => $bid->cost_sell,
                    'current_highest_price' => $bid->cost_sell,
                    'current_highest_bidder_id' => $request->user_id,
                    'current_highest_bidder_name' => $request->user_name,
                    'status' => Config::get('constants.bid_status.success'),
                );
            }
            

            DB::beginTransaction();
            $this->user_bidRepository->create($bid_session_user_current);
            $bid->update($bid_update);
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * update bid in case amount input is less than current hightest 
     * @param $bid, $request
     * @return void
     */
    public function amountLessHightest($bid, $request){
        try {
            $bid_session_user_current = array(
                'user_id' => $request->user_id,
                'bid_id' => $bid->id,
                'real_bid_amount' => $request->real_bid_amount,
                'bid_amount' => $request->real_bid_amount,
                'bid_type' => $this->bid_type_manual,
                'created_at'=> $this->time_now
            );

            if($request->real_bid_amount+$this->bid_amount_step <= $bid->current_highest_price){
                $bid_session_user_highest = array(
                    'user_id' => $bid->current_highest_bidder_id,
                    'bid_id' => $bid->id,
                    'real_bid_amount' => $bid->current_highest_price,
                    'bid_amount' => $request->real_bid_amount+$this->bid_amount_step,  
                    'bid_type' => $this->bid_type_auto,
                    'created_at'=> $this->time_now
                );
                $bid_update = array('current_price' => $request->real_bid_amount+$this->bid_amount_step);
            }else{
                $bid_session_user_highest = array(
                    'user_id' => $bid->current_highest_bidder_id,
                    'bid_id' => $bid->id,
                    'real_bid_amount' => $bid->current_highest_price,
                    'bid_amount' => $bid->current_highest_price,  
                    'bid_type' => $this->bid_type_auto,
                    'created_at'=> $this->time_now
                );
                $bid_update = array('current_price' => $bid->current_highest_price);
            }
            

            DB::beginTransaction();
            $this->user_bidRepository->multiCreate([$bid_session_user_highest,$bid_session_user_current]);
            $bid->update($bid_update);
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * update bid in case amount input is equal current hightest 
     * @param $bid, $request
     * @return void
     */
    public function amountEqualHightest($bid, $request){
        try {
            $bid_session_user_current = array(
                'user_id' => $request->user_id,
                'bid_id' => $bid->id,
                'real_bid_amount' => $request->real_bid_amount,
                'bid_amount' => $request->real_bid_amount,
                'bid_type' => $this->bid_type_manual,
                'created_at'=> $this->time_now
            );

            $bid_session_user_highest = array(
                'user_id' => $bid->current_highest_bidder_id,
                'bid_id' => $bid->id,
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
            ]);
            DB::commit();
            
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }


    /**
     * update bid in case amount input is more than current hightest 
     * @param $bid, $request
     * @return void
     */
    public function amountBetterHightest($bid, $request){
        try {
            $bid_session_user_current = array(
                'user_id' => $request->user_id,
                'bid_id' => $bid->id,
                'real_bid_amount' => $request->real_bid_amount,
                'bid_amount' => $bid->current_highest_price+$this->bid_amount_step,
                'bid_type' => $this->bid_type_manual,
                'created_at'=> $this->time_now
            );

            $bid_session_user_highest = array(
                'user_id' => $bid->current_highest_bidder_id,
                'bid_id' => $bid->id,
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
     * @param $bid, $request
     * @return void
     */
    public function amountCostSell($bid, $request){
        try {
            $bid_session_user_current = array(
                'user_id' => $request->user_id,
                'bid_id' => $bid->id,
                'real_bid_amount' => $request->real_bid_amount,
                'bid_amount' => $bid->cost_sell,
                'bid_type' => $this->bid_type_manual,
                'created_at'=> $this->time_now
            );
            
            $bid_session_user_highest = array(
                'user_id' => $bid->current_highest_bidder_id,
                'bid_id' => $bid->id,
                'real_bid_amount' => $bid->current_highest_price,
                'bid_amount' => $bid->current_highest_price,  
                'bid_type' => $this->bid_type_auto,
                'created_at'=> $this->time_now
            );

            DB::beginTransaction();
            $this->user_bidRepository->multiCreate([$bid_session_user_highest,$bid_session_user_current]);
            $bid->update([
                'current_price' => $bid->cost_sell,
                'current_highest_price' => $bid->cost_sell,
                'current_highest_bidder_id' => $request->user_id,
                'current_highest_bidder_name' => $request->user_name,
                'status' => Config::get('constants.bid_status.success'),
                'time_end' => $this->time_now
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }
}

