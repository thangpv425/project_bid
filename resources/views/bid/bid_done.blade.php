@extends('bid.bid_detail')
@section('bid')
	<div class="bid-done text-center">
		<div class="avatar">
			<img class="img-circle" src="http://dummyimage.com/800x600/4d494d/686a82.gif&text=placeholder+image" alt="placeholder+image">
			<img class="img-responsive vong" src="{{Storage::url('image/win-vong.svg')}}">
		</div>
		<h3 class="user-win">Nguyen tien phu</h3>
		<span>Giá kết thúc</span>
		<span class="bid-cost-done">580k</span>
	</div>
@endsection
