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
        $userBids = $this->user_bidRepository->getBidHistory($id);
        //dd($userBids);
        return view('bid.bid_current')->with(compact('bid'))->with(compact('userBids'));
    }

    /**
     * @param $request{user_id, real_bid_amound}
     * @return $current_price
     */
    public function postBid(Request $request, $id) {

        $bid = $this->bidRepository->getBid($id);

        if(!$bid){
            $userBids = $this->user_bidRepository->getBidHistory($id);
            $historyView = view('bid.bid-history-item')->with(compact('userBids'))->render();
            return array(
                'type'=> 'error',
                'data' => 'bid not valid',
                'bid_history' => $historyView,
            );
        }


        //return $bid->current_highest_bidder_id == null ? 1:0;
        if($bid->current_highest_bidder_id == null) {
            if($request->real_bid_amount < $bid->cost_begin){

                $userBids = $this->user_bidRepository->getBidHistory($id);
                $historyView = view('bid.bid-history-item')->with(compact('userBids'))->render();

                return array(
                    'type'=> 'error',
                    'data' => 'bid must more than cost begin',
                    'bid_history' => $historyView,
                    );
            }
            //return "nguoi tra gia lan dau";
            $this->amountFirst($bid, $request);
        } else {
            if ($request->real_bid_amount <  ($bid->current_price+$this->bid_amount_step)) {

                $userBids = $this->user_bidRepository->getBidHistory($id);
                $historyView = view('bid.bid-history-item')->with(compact('userBids'))->render();
                return array(
                    'type'=> 'error',
                    'data' => 'bid must more than current price',
                    'bid_history' => $historyView,
                );
            }

            if($request->real_bid_amount < $bid->current_highest_price){
                //return "tra gia nho hơn current hightest";
                $this->amountLessHightest($bid, $request);
            }

            if($request->real_bid_amount == $bid->current_highest_price){
                //return  "tra gia bang current hightest";
                $this->amountEqualHightest($bid, $request);
            }

            if($request->real_bid_amount > $bid->current_highest_price){
                if ($bid->cost_sell != null && $request->real_bid_amount >= $bid->cost_sell) {
                    $this->amountCostSell($bid, $request);
                } else {
                    $this->amountBetterHightest($bid, $request);
                }
            }
        }


        $userBids = $this->user_bidRepository->getBidHistory($id);
        $historyView = view('bid.bid-history-item')->with(compact('userBids'))->render();

        $respont = array(
            'type' => 'success',
            'data' => array(
                'current_highest_bidder_id'=> $bid->current_highest_bidder_id,
                'current_highest_bidder_name'=>$bid->current_highest_bidder_name,
                'current_price'=> $bid->current_price,
            ),
            'bid_history' => $historyView,
        );

        return $respont;
    }
    /**
     * update bid in case nguoi tra gia lan dau
     * @param $bid, $request
     * @return void
     */
    public function amountFirst($bid, $request){
        try {

            if($bid->cost_sell != null && $request->real_bid_amount >= $bid->cost_sell){
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
                    'time_end' => $this->time_now
                );

            } else {
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
            } else {
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
            $this->user_bidRepository->multiCreate([$bid_session_user_current, $bid_session_user_highest]);
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
            $this->user_bidRepository->multiCreate([$bid_session_user_current, $bid_session_user_highest]);
            $bid->update([
                'current_price' => $request->real_bid_amount,
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
        if ($request->user_id == $bid->current_highest_bidder_id) {
            $latestUserBid = $this->user_bidRepository->latestRow();
            try {
                DB::beginTransaction();
                $latestUserBid->update(array(
                    'real_bid_amount' => $request->real_bid_amount,
                    'type' => $this->bid_type_manual,
                ));

                $bid->update(array(
                    'current_highest_price' => $request->real_bid_amount,
                ));
                DB::commit();
            } catch (\Exception $exception) {
               DB::rollback();
            }
        } else {
            if ($request->real_bid_amount < $bid->current_highest_price+$this->bid_amount_step) {
                $bid_amount =  $request->real_bid_amount;
                $current_price = $request->real_bid_amount;
            } else {
                $bid_amount =  $bid->current_highest_price+$this->bid_amount_step;
                $current_price = $bid->current_highest_price+$this->bid_amount_step;
            }

            $bid_session_user_current = array(
                'user_id' => $request->user_id,
                'bid_id' => $bid->id,
                'real_bid_amount' => $request->real_bid_amount,
                'bid_amount' => $bid_amount,
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

            $bid_update = array(
                'current_price' => $current_price,
                'current_highest_price' => $request->real_bid_amount,
                'current_highest_bidder_id' => $request->user_id,
                'current_highest_bidder_name' => $request->user_name
            );

            try {
                DB::beginTransaction();
                $this->user_bidRepository->multiCreate([$bid_session_user_highest,$bid_session_user_current]);
                $bid->update($bid_update);
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollback();
                return false;
            }
        }
    }
    /**
     * update bid in case amount input is more than cost sell
     * @param $bid, $request
     * @return void
     */
    public function amountCostSell($bid, $request){
        $real_bid_amount = $bid->cost_sell;
        try {
            $bid_session_user_current = array(
                'user_id' => $request->user_id,
                'bid_id' => $bid->id,
                'real_bid_amount' => $real_bid_amount,
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

            if($request->user_id != $bid->current_highest_bidder_id) {
                $this->user_bidRepository->multiCreate([$bid_session_user_highest,$bid_session_user_current]);
            } else {
                $userBidLatest = $this->user_bidRepository->latestRow();
                $userBidLatest->update($bid_session_user_current);
            }

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