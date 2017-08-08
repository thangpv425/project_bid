@extends('layouts.app')
@section('content')
	<div class="container bid-detail">
		<div class="row">
			<div class="col-md-6 product" >
				<div>
					<div class="img-cover">
						<img src="http://dummyimage.com/800x600/4d494d/686a82.gif&text=placeholder+image" alt="placeholder+image" style="width: 100%">
					</div>
					<div class="product-info">
						<h3 class="product-name">Zippo COTY 2016 Armor Facet</h3>
						<div class="product-owner">
							<img src="http://dummyimage.com/800x600/4d494d/686a82.gif&text=placeholder+image" alt="placeholder+image" class="avatar img-circle">
							<span class="product-owner-name">Linh</span>
						</div>
						<div class="description">
							<p>Siêu phẩm Zippo COTY 2016 với chủ đề Armor Facet, được thiết kế họa tiết trên toàn bộ bề mặt và xung quanh chiếc Zippo với công nghệ khắc cắt đa chiều 360 độ tinh xảo và phức tạp tạo nên một chiếc Zippo vẻ ngoài nam tính, đầy mạnh mẽ và thu hút. Đây là một trong 13000 chiếc Zippo được sản xuất phiên bản Limited trong Collection Of The Year 2016.
							Được làm từ chất liệu Satin Chrome tạo vẻ ngoài siêu sang trọng, hơn nữa với lớp “áo giáp” Armor  vỏ bên ngoài cứng cáp, chống va đập và bền bỉ.
							Zippo Armor Facet – Khẳng định đẳng cấp phái mạnh.
							Chất liệu: Satin Chrome
							Xuất xứ: Hàng chính hãng USA
							Sản xuất: 2016</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="bid">
					@yield('bid')
					<div class="bid-history">
						<h3 class="title">Lịch sử đấu giá (15)</h3>
						<ul class="list-group">
							@for($i=1; $i<10; $i++)
						    	@include('bid.bid-history-item')
						    @endfor
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
