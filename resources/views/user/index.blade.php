@extends('layouts.app')
@section('content')
	<div class="container user-page">
		<div class="row">
			<div class="col-sm-4">
				<ul class="user-page-menu">
					<li class="user-page-menu-item holder"><a href="#">Quản lý giao dịch</a></li>
					<li class="user-page-menu-item"><a href="#">Phiên đấu tham gia</a></li>
					<li class="user-page-menu-item"><a href="#">Thông tin tài khoản</a></li>
					<li class="user-page-menu-item"><a href="#">Tạo mật khẩu</a></li>
				</ul>
			</div>
			<div class="col-sm-8">
				@yield('user-page')
			</div>
		</div>
	</div>
@endsection
