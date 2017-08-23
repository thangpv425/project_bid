@extends('layouts.app')
@section('content')
	<div class="container user-page">
		<div class="row">
			<div class="col-sm-3">
				<ul class="user-page-menu">
					<li class="user-page-menu-item"><a href="{{route('user.joining-bids')}}">Cuộc đấu giá đang tham gia</a></li>
					<li class="user-page-menu-item dropdown-choose"><a href="#">Cuộc đấu giá đã thành công</a>
						<ul class="choose">
							<li><a href="{{route('user.paying-bids')}}">Đang trong quá trình thanh toán</a></li>
							<li><a href="{{route('user.paid-bids')}}">Thanh toán hoàn tất</a></li>
							<li><a href="{{route('user.cancel-bids')}}">Bị hủy</a></li>
						</ul>
					</li>
					<li class="user-page-menu-item"><a href="{{route('user.fail-bids')}}">Cuộc đấu giá không thành công</a></li>
					<li class="user-page-menu-item"><a href="{{route('user.profile')}}">Thông tin người dùng</a></li>
					<li class="user-page-menu-item"><a href="{{route('mocup-list-mail')}}">Mail box</a></li>
				</ul>
			</div>

			<div class="col-sm-9">
				@yield('user-page')
			</div>
		</div>
	</div>
	<script>
		$(function () {
            $('.dropdown-choose').click(function () {
                $('.choose').toggleClass('display-block');
            });
        })

	</script>

@endsection
