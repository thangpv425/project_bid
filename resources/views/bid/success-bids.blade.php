@extends('layouts.app')
@section('content')
    @include('layouts.slide')
    <div class="home">
        <div class="container">
            <h1>Các sản phẩm đang đấu giá</h1>
            @if (count($successBids) > 0)
                <div class="row list-item" id="current_bid">
                    @include('bid.item_bid_done')
                </div>
            @endif
            {{$successBids->links()}}
        </div>
    </div>
@endsection
