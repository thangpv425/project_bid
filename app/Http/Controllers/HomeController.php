<?php

namespace App\Http\Controllers;

use App\Repositories\Bid\BidRepositoryInterface;
use Illuminate\Http\Request;

class HomeController extends Controller {

    protected $bid;

    public function __construct(BidRepositoryInterface $bid) {
        $this->bid = $bid;
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request) {
        $currentBids = $this->bid->getCurrentBids();
        $successBids = $this->bid->getSuccessBids();
        if ($request->ajax()) {
            if ($request->input('type') == 'current_bid') {
                $view = view('bid.item', compact('currentBids'))->render();
            }

            if ($request->input('type') == 'success_bid') {
                $view = view('bid.item_bid_done', compact('successBids'))->render();
            }

            return response()->json([
                'html' => $view,
                'type' => $request->input('type'),
            ]);
        }

        return view('layouts.home')->with(compact('currentBids', 'successBids'));
    }
}
