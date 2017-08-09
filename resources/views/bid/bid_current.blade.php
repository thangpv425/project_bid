@extends('bid.bid_detail')
@section('bid')
	<div class="bid-info">
		<div class="time text-center">
			<span class="time-day">01</span> Ngày <span class="time-hour">07 : 10</span>
		</div>
		<div class="time-end text-center">Kết thúc vào 21:00:00, 08/08/2017</div>
		<div class="bid-current">
			<span class="title">Giá hiện tại</span>
			<span class="bid-current-info">740K</span>
			bởi
			<span class="hightest-bid-user">Linh</span>
		</div>
	</div>
	@include('bid.bid-join')
@endsection
