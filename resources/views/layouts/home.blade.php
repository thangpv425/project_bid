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
                        <button class="btn btn-primary">Xem thêm sản phẩm đã đấu giá</button>
                    </div>
                </div>
                <div class="ajax-load text-center" style="display:none;" id="ajax-load-current">
                    <p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Đang load</p>
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
                        <button class="btn btn-primary">Xem thêm sản phẩm đã đấu giá</button>
                    </div>
                </div>
                <div class="ajax-load text-center" style="display:none;" id="ajax-load-success">
                    <p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Đang load</p>
                </div>
            @else
                <p class="text-center">Hiện không có phiên đấu giá nào thành công</p>
            @endif
		</div>
	</div>
@endsection

{!! Html::script('https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js') !!}
{!! Html::script('js/home.js') !!}
