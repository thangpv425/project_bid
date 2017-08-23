@extends('user.index')
@section('user-page')
	<div class="bid-manager-tab" data-menu='1'>
		<ul class="nav nav-tabs text-center">
		    <li class="active"><a data-toggle="tab" href="{{route('user.joining-bids')}}">Đang đấu giá</a></li>
		    <li><a data-toggle="tab" href="#menu1">Đấu giá thành công</a></li>
		    <li><a data-toggle="tab" href="#menu2">Đấu giá thất bại</a></li>
		</ul>

		<div class="tab-content">
		    <div id="home" class="tab-pane fade in active">
		      	<div class="row">
					@if (!empty($bids))
						@foreach($bids as $bid)
							<div class="col-sm-3 col-md-3">
								@include('bid.item')
							</div>
						@endforeach
					@endif
		      	</div>
		    </div>
		    <div id="menu1" class="tab-pane fade">
		    	<div class="row">
					@if (!empty($bids))
						@foreach($bids as $bid)
							<div class="col-sm-3 col-md-3">
								@include('bid.item-bid-done')
							</div>
						@endforeach
					@endif
		      	</div>
		    </div>
		    <div id="menu2" class="tab-pane fade">
		    	<div class="row">
					@if (!empty($bids))
						@foreach($bids as $bid)
							<div class="col-sm-3 col-md-3">
								@include('bid.item')
							</div>
						@endforeach
					@endif
		      	</div>
		    </div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			var menu = $('.bid-manager-tab').attr('data-menu');
			$('.user-page-menu li:nth-child('+menu+')').addClass('holder');
		})
	</script>
@endsection
