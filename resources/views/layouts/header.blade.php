<nav class="navbar navbar-default navbar-static-top" style="margin-bottom: 0">
	<div class="container">
		<div class="navbar-header">

			<!-- Collapsed Hamburger -->
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
				<span class="sr-only">Toggle Navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			<!-- Branding Image -->
			<a class="navbar-brand" href="{{route('home')}}">
				<img src="{{asset('/storage/image/logo.svg')}}">
			</a>
		</div>

		<div class="collapse navbar-collapse" id="app-navbar-collapse">
			<!-- Left Side Of Navbar -->
			<ul class="nav navbar-nav">
				&nbsp;
			</ul>

			<!-- Right Side Of Navbar -->
			<ul class="nav navbar-nav navbar-left">
				<!-- Authentication Links -->
				<li><a href="#">Zippo</a></li>
				<li><a href="#">Figure</a></li>
				<li><a href="#">Mô hình</a></li>
				<li><a href="#">Nước hoa</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				@if(!Auth::guest())
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
							{{Auth::user()->nickname}} <span class="caret"></span>
						</a>

						<ul class="dropdown-menu" role="menu">
							<li>
								<a href="{{route('user.profile')}}">
									<img src="{{asset('/storage/image/trangcanhan.svg')}}" class="icon-menu">Trang cá nhân
								</a>
							</li>
							<li>
								<a href="{{route('mocup-bid-manager')}}">
									<img src="{{asset('/storage/image/quanli.svg')}}" class="icon-menu">Quản lý giao dịch
								</a>
							</li>
							<li>
								<a href="#">
									<img src="{{asset('/storage/image/phiendau.svg')}}" class="icon-menu">Phiên đấu giá tham gia
								</a>
							</li>
							<li>
								<a href="{{ route('logout') }}"
									onclick="event.preventDefault();
											 document.getElementById('logout-form').submit();">
									<img src="{{asset('/storage/image/thoat.svg')}}" class="icon-menu">
									Đăng xuất
								</a>

								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									{{ csrf_field() }}
								</form>
							</li>
						</ul>
					</li>
				@else
					<li><a class="btn-login" href="{{route('login')}}">Đăng nhập</a></li>
					<li><a class="btn-rigister btn-green" href="{{route('register')}}">Đăng ký</a></li>
				@endif			
			</ul>
		</div>
	</div>
</nav>
