<?php

namespace App\Http\Controllers\Bid;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bid;
use DB;
use App\Repositories\Bid\BidRepositoryInterface;
class BidController extends Controller
{
	protected $bidRepository;
	public function __construct(BidRepositoryInterface $bidRepository) {
		$this->bidRepository = $bidRepository;
	}

    /**
     * @param $id
     * @return Bid
     */
    public function getBidCurrent($id){
    	return $this->bidRepository->find($id);
    }
}
