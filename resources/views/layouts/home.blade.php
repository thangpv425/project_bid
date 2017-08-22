@extends('layouts.app')
@section('content')
	@include('layouts.slide')
	<div class="home">
		<div class="container">
			<h1>Các sản phẩm đang đấu giá</h1>
            @if (count($currentBids) > 0)
                <div class="row list-item" id="current_bid">
                    @include('bid.item')
                </div>

                <div class="row">
                    <div class="col-md-offset-4 col-md-4 text-center" id="show-more-current-bid">
                        <a class="btn btn-primary" href="{{route('current-bids')}}">Xem thêm sản phẩm đã đấu giá</a>
                    </div>
                </div>
            @else
                <p class="text-center">Hiện không có phiên đấu giá nào đang diễn ra</p>
            @endif


            <h1>Các sản phẩm đã đấu giá</h1>
            @if (count($successBids) > 0)
                <div class="row list-item" id="success_bid">
                    @include('bid.item_bid_done')
                </div>
                <div class="row">
                    <div class="col-md-offset-4 col-md-4 text-center" id="show-more-success-bid">
                        <a class="btn btn-primary" href="{{route('success-bids')}}">Xem thêm sản phẩm đã đấu giá</a>
                    </div>
                </div>
            @else
                <p class="text-center">Hiện không có phiên đấu giá nào thành công</p>
            @endif
		</div>
	</div>
@endsection
