@extends('layouts.app')
@section('content')
	<div class="container user-page">
		<div class="row">
			<div class="col-sm-3">
				<ul class="user-page-menu">
					<li class="user-page-menu-item"><a href="{{route('mocup-bid-manager')}}">Quản lý giao dịch</a></li>
					<li class="user-page-menu-item"><a href="#">Phiên đấu tham gia</a></li>
					<li class="user-page-menu-item"><a href="{{route('mocup-user-info')}}">Thông tin tài khoản</a></li>
					<li class="user-page-menu-item"><a href="{{route('mocup-change-email')}}">Đổi email</a></li>
					<li class="user-page-menu-item"><a href="{{route('mocup-change-password')}}">Đổi mật khẩu</a></li>
					<li class="user-page-menu-item"><a href="{{route('mocup-delete-account')}}">Xóa tài khoản</a></li>
					<li class="user-page-menu-item"><a href="{{route('mocup-list-mail')}}">List mail</a></li>
				</ul>
			</div>
			<div class="col-sm-9">
				@yield('user-page')
			</div>
		</div>
	</div>
@endsection
