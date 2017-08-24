<li class="list-group-item user-bid-info">
	<div class="row">
		<div class="col-xs-6">
			<img src="http://dummyimage.com/800x600/4d494d/686a82.gif&text=placeholder+image" alt="placeholder+image" class="avatar img-circle">
			<span>{{$userBid->nickname}}</span>
		</div>
		<div class="col-xs-2">
			<span class="bid_amount">{{$userBid->bid_amount}}</span>
		</div>
		<div class="col-xs-4">
			@if (empty($userBid->updated_at))
				<p class="time-hour">{{$userBid->created_at->format('H:i:s')}}</p>
				<p class="time-day">{{$userBid->created_at->format('Y-m-d')}}</p>
			@else
				<p class="time-hour">{{$userBid->updated_at->format('H:i:s')}}</p>
				<p class="time-day">{{$userBid->updated_at->format('Y-m-d')}}</p>
			@endif

		</div>
	</div>
</li>
