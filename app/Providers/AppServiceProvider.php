<?php

namespace App\Providers;

use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Hash\HashRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\Hash\HashRepository;
use App\Repositories\Bid\BidRepository;
use App\Repositories\Bid\BidRepositoryInterface;
use App\Repositories\User_Bid\UserBidRepository;
use App\Repositories\User_Bid\UserBidRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(HashRepositoryInterface::class, HashRepository::class);
        $this->app->bind(BidRepositoryInterface::class, BidRepository::class);
        $this->app->bind(UserBidRepositoryInterface::class, UserBidRepository::class);

    }
}
