<?php

namespace App\Http\Controllers;

use App\Repositories\Bid\BidRepositoryInterface;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    protected $bid;

    protected $currentBids;

    protected $successBids;

    public function __construct(BidRepositoryInterface $bid) {
        $this->bid = $bid;
    }

    /**
     * @return $this
     */
    public function index() {
        $currentBids = $this->bid->getCurrentBids();
        $successBids = $this->bid->getSuccessBids();

        return view('layouts.home')->with('currentBids', $currentBids)->with('successBids', $successBids);
    }

    public function currentBids() {
        $bids = $this->bid->getCurrentBids();
        return view('bid.current-bids')->with(compact('bids'));
    }

    public function successBids() {
        $bids = $this->bid->getSuccessBids();
        return view('bid.success-bids')->with(compact('bids'));
    }
}
