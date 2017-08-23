<?php

namespace App\Repositories\Bid;

interface BidRepositoryInterface {
   public function getBid($id);

   public function getCurrentBids();

   public function getSuccessBids();

   public function getJoiningBids($userId);

    public function getUnpaidBids($userId);

    public function getPaidBids($userId);

    public function getCanceledBids($userId);
}
