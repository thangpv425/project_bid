<?php

namespace App\Http\Controllers;

use App\Repositories\Bid\BidRepositoryInterface;
use Illuminate\Http\Request;

class HomeController extends Controller {

    protected $bid;

    protected $currentBids;

    protected $successBids;

    public function __construct(BidRepositoryInterface $bid) {
        $this->bid = $bid;
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {

        $this->currentBids = $this->bid->getCurrentBids();
        $this->successBids = $this->bid->getSuccessBids();

        if ($request->ajax()) {
            $view = $this->getPaginateView($request->input('type'));
            return response()->json([
                'html' => $view,
                'type' => $request->input('type'),
            ]);
        }

        return view('layouts.home')->with('currentBids',$this->currentBids)->with('successBids', $this->successBids);
    }

    protected function getPaginateView($type) {
        if ($type == 'current_bid') {
            $view = view('bid.item')->with('currentBids', $this->currentBids)->render();
        }

        if ($type == 'success_bid') {
            $view = view('bid.item_bid_done')->with('successBids', $this->successBids)->render();
        }

        return $view;
    }
}
