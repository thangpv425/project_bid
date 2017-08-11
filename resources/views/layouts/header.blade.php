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
			<a class="navbar-brand" href="{{route('mocup-home')}}">
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
				<li><a href="#">Mo hinh</a></li>
				<li><a href="#">Nuoc hoa</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				@if(!Auth::guest())
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
							aaaaaaaa <span class="caret"></span>
						</a>

						<ul class="dropdown-menu" role="menu">
							<li>
								<a href="#">
									<img src="{{asset('/storage/image/trangcanhan.svg')}}" class="icon-menu">Trang ca nhan
								</a>
							</li>
							<li>
								<a href="{{route('mocup-bid-manager')}}">
									<img src="{{asset('/storage/image/quanli.svg')}}" class="icon-menu">Quan li giao dich
								</a>
							</li>
							<li>
								<a href="#">
									<img src="{{asset('/storage/image/phiendau.svg')}}" class="icon-menu">Phien dau tham gia
								</a>
							</li>
							<li>
								<a href="{{route('mocup-user-info')}}">
									<img src="{{asset('/storage/image/chinhsua.svg')}}" class="icon-menu">Chinh sua tai khoan
								</a>
							</li>
							<li>
								<a href="{{ route('logout') }}"
									onclick="event.preventDefault();
											 document.getElementById('logout-form').submit();">
									<img src="{{asset('/storage/image/thoat.svg')}}" class="icon-menu">
									Logout
								</a>

								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
									{{ csrf_field() }}
								</form>
							</li>
						</ul>
					</li>
				@else
					<li><a class="btn-login" href="{{route('login')}}">Dang nhap</a></li>
					<li><a class="btn-rigister btn-green" href="{{route('register')}}">Dang ki</a></li>
				@endif			
			</ul>
		</div>
	</div>
</nav>
