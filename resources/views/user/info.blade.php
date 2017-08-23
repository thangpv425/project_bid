@extends('user.index')
@section('user-page')
	<div class="user-info" data-menu='4'>
		@if (session('message'))
			@if (session('message')['type'] == 'success')
				<div class="alert alert-success">
					{{session('message')['data']}}
				</div>
			@else
				<div class="alert alert-danger">
					{{session('message')['data']}}
				</div>
			@endif
		@endif
		<div class="row user-info-bid">
			<div class="col-sm-3">
				<div class="avatar">
					<img class="img-responsive" src="http://dummyimage.com/800x600/4d494d/686a82.gif&text=placeholder+image" alt="placeholder+image" style="width: 125px; height: 125px; margin-bottom: 20px">
					<button class="btn btn-primary">Đổi hình đại diện</button>
				</div>
			</div>
			<div class="col-sm-9">
				<h3 class="name">{{$user->nickname}}</h3>
				<div class="clearfix">
					<div class="pull-left">
						<span>0</span>
						<span>Sản phẩm đã đấu</span>
					</div>
					<div class="pull-left">
						<span>0</span>
						<span>Sản phẩm đã thắng</span>
					</div>
				</div>
			</div>
		</div>

		<div class="user-info-commom">
			<form method="POST" action="{{route('user.update-profile')}}">
				{{ csrf_field() }}
				<h3 class="title">Thông tin chung</h3>
				<div class="form-group row">
					<label class="col-md-2">Nickname</label>
					<span class="col-md-5">{{$user->nickname}}</span>
				</div>
				<div class="form-group row" id="email">
					<label class="col-md-2">Địa chỉ email</label>
					<span class="col-md-5">{{$user->email}}</span>
					<a class="col-md-5 text-right" href="{{route('user.change-email')}}">Thay đổi</a>
				</div>
				<div class="form-group row">
					<label class="col-md-2">Mật khẩu</label>
					<span class="col-md-5">*******</span>
					<a class="col-md-5 text-right" href="{{route('user.change-password')}}">Thay đổi</a>
				</div>
				<h3 class="title">Thông tin địa chỉ</h3>
				<div class="form-group{{ $errors->has('ship_name') ? ' has-error' : '' }}">
					<div class="row">
						<label class="col-md-2">Tên</label>
						<input type="text" class="col-md-5" name="ship_name" value="{{$user->ship_name}}">
					</div>
					@if ($errors->has('ship_name'))
						<span class="help-block">
							<strong>{{ $errors->first('ship_name') }}</strong>
						</span>
					@endif
				</div>
				<div class="form-group{{ $errors->has('ship_tel') ? ' has-error' : '' }}">
					<div class="row">
						<label class="col-md-2">Số điện thoại</label>
						<input type="text" class="col-md-5" name="ship_tel" value="{{$user->ship_tel}}">
					</div>
					@if ($errors->has('ship_tel'))
						<span class="help-block">
							<strong>{{ $errors->first('ship_tel') }}</strong>
						</span>
					@endif
				</div>

				<div class="form-group row">
					<div class="col-md-3">
						<input type="radio" name="address-type" value="address" checked >Sử dụng địa chỉ riêng<br>
					</div>
					<div class="col-md-3" >
						<input  type="radio" name="address-type" value="post-office">Gửi đến bưu điện<br>
					</div>
				</div>

				<div id="address" class="radio-element">
					<input type="hidden" class="p-country-name" value="Vietnam">
					<div class="form-group{{ $errors->has('ship_zip') ? ' has-error' : '' }}">
						<div class="row">
							<label class="col-md-2">Post code</label>
							<input type="text" class="p-postal-code col-md-5" size="8" maxlength="8" name="ship_zip">
						</div>
						@if ($errors->has('ship_zip'))
							<span class="help-block">
							<strong>{{ $errors->first('ship_zip') }}</strong>
						</span>
						@endif
					</div>
					<div class="form-group{{ $errors->has('ship_address') ? ' has-error' : '' }}">
						<div class="row">
							<label class="col-md-2" name="ship_address">Địa chỉ</label>
							<input type="text" class="p-region p-locality p-street-address p-extended-address col-md-5" />
						</div>
						@if ($errors->has('ship_address'))
							<span class="help-block">
							<strong>{{ $errors->first('ship_address') }}</strong>
						</span>
						@endif
					</div>
				</div>

				<div class="none radio-element" id="post-office">
					<div class="form-group{{ $errors->has('ship_prefecture') ? ' has-error' : '' }} row">
						<label class="col-md-2">Tỉnh thành</label>
						<select class="col-md-5" name="ship_prefecture" style="height:27.78px;">
							@foreach($prefectures as $key => $value)
								<option value="{{$key}}">{{$value}}</option>
							@endforeach
						</select>
						@if ($errors->has('ship_prefecture'))
							<span class="help-block">
							<strong>{{ $errors->first('ship_prefecture') }}</strong>
						</span>
						@endif
					</div>
					<div class="form-group{{ $errors->has('post_office') ? ' has-error' : '' }}">
						<div class="row">
							<label class="col-md-2" >Tên bưu điện</label>
							<input class="col-md-5" type="text" name="post_office"/>
						</div>
						@if ($errors->has('post_office'))
							<span class="help-block">
							<strong>{{ $errors->first('post_office') }}</strong>
						</span>
						@endif
					</div>
				</div>
				<div class="form-group text-center ">
					<button class="btn btn-primary " style="width:100px;">Lưu</button>
				</div>

			</form>
		</div>

		<script type="text/javascript">
			$(document).ready(function(){
				var menu = $('.user-info').attr('data-menu');
				$('.user-page-menu li:nth-child('+menu+')').addClass('holder');
				$(':radio').change(function(evt) {
					var type = $(this).val();
					target = $('#'+type);
					$(".radio-element").not(target).hide();
					$(target).show();
                })
            })
		</script>
		<script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
	</div>
@endsection
