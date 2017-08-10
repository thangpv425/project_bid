@extends('user.index')
@section('user-page')
	<div class="bid-manager-tab" data-menu='1'>
		<ul class="nav nav-tabs text-center">
		    <li class="active"><a data-toggle="tab" href="#home">Đang đấu giá</a></li>
		    <li><a data-toggle="tab" href="#menu1">Đấu giá thành công</a></li>
		    <li><a data-toggle="tab" href="#menu2">Đấu giá thất bại</a></li>
		</ul>

		<div class="tab-content">
		    <div id="home" class="tab-pane fade in active">
		      	<div class="row">
		      		@for($i=0; $i<8; $i++)
						<div class="col-sm-6 col-md-4">
							@include('bid.item')
						</div>
					@endfor
		      	</div>
		    </div>
		    <div id="menu1" class="tab-pane fade">
		    	<div class="row">
		      		@for($i=0; $i<8; $i++)
						<div class="col-sm-6 col-md-4">
							@include('bid.item_bid_done')
						</div>
					@endfor
		      	</div>
		    </div>
		    <div id="menu2" class="tab-pane fade">
		    	<div class="row">
		      		@for($i=0; $i<8; $i++)
						<div class="col-sm-6 col-md-4">
							@include('bid.item')
						</div>
					@endfor
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
