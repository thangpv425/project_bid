@extends('bid.bid_detail')
@section('bid')
	<div class="bid-info" data="{{$bid->id}}">
		<div class="time text-center">
			<span class="time-day">01</span> Ngày <span class="time-hour">07 : 10</span>
		</div>
		<div class="time-end text-center">Kết thúc vào 21:00:00, 08/08/2017</div>
		<div class="bid-current">
			<span class="title">Giá hiện tại</span>
			@if($bid->current_price != null)
				<span class="bid-current-info" data={{$bid->current_price+500}} >{{$bid->current_price}}</span>bởi
			@else
				<span class="bid-current-info" data={{$bid->cost_begin}} >{{$bid->cost_begin}}</span>
			@endif
			<span class="hightest-bid-user">{{$bid->current_highest_bidder_name}}</span>
		</div>
	</div>
	<input type="hidden" name="bid_id" value="{{$bid->id}}">
	<div class="input-bid">
		@if($bid->current_price != null)
			<input type="text" name="amount" placeholder="Đặt giá tối thiểu từ {{$bid->current_price+500}} hoặc hơn">
		@else
			<input type="text" name="amount" placeholder="Đặt giá tối thiểu từ {{$bid->cost_begin}} hoặc hơn">
		@endif
		<input type="text" name="name" placeholder="name">
		<input type="number" name="id" placeholder="id">
		<button>Đặt giá</button>
	</div>

@endsection
