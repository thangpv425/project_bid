@extends('layouts.app')
@section('content')
    @include('layouts.slide')
    <div class="home">
        <div class="container">
            <h1 class="text-center">Danh sách cuộc đấu giá</h1>
            <div class="row">
                <div class="col-md-3" style="margin: 20px 0px 50px 0px;">
                    <a href="?bid_type=bidding">Bidding</a>
                    <a href="?bid_type=end">End</a>
                    <a href="{{route('bid-list')}}">all</a>
                </div>

            </div>

            @if (count($bids) > 0)
                <div class="row list-item" id="current_bid">
                    @foreach($bids as $bid)
                        <div class="col-sm-3 col-md-3">
                            @include('bid.item')
                        </div>
                    @endforeach
                </div>
            @endif
            {{$bids->links()}}
        </div>
    </div>
@endsection