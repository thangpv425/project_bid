<?php

namespace App\Repositories\Bid;

interface BidRepositoryInterface {
   public function getBid($id);

   public function getCurrentBids();

   public function getSuccessBids();
}
