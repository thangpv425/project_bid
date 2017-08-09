@extends('layouts.app')
@section('content')
	@include('layouts.slide')
	<div class="home">
		<div class="container">
			<h1>Các sản phẩm đang đấu giá</h1>
			<div class="row list-item">
				@for($i=0; $i<8; $i++)
					<div class="col-sm-3 col-md-3">
						@include('bid.item')
					</div>
				@endfor
			</div>

			<h1>Các sản phẩm đã đấu giá</h1>
			<div class="row list-item">
				@for($i=0; $i<8; $i++)
					<div class="col-sm-3 col-md-3">
						@include('bid.item_bid_done')
					</div>
				@endfor
			</div>
		</div>	
	</div>
@endsection
