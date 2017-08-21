@extends('user.index')
@section('user-page')
	<div class="user-info" data-menu='3'>
		<div class="row user-info-bid">
			<div class="col-sm-3">
				<div class="avatar">
					<img class="img-responsive" src="http://dummyimage.com/800x600/4d494d/686a82.gif&text=placeholder+image" alt="placeholder+image" style="width: 125px; height: 125px; margin-bottom: 20px">
					<button class="btn btn-primary">Đổi hình đại diện</button>
				</div>
			</div>
			<div class="col-sm-9">
				<h3 class="name">{{Auth::user()->nickname}}</h3>
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
			<form>
				<h3 class="title">Thông tin chung</h3>
				<div class="form-group">
					<label>Tên người dùng</label>
					<input type="text" name="name" class="form-control">
				</div>
				<div class="form-group">
					<label>Địa chỉ email</label>
					<input type="email" name="name" class="form-control">
				</div>
				<h3 class="title">Địa chỉ nhận hàng</h3>
				<div class="form-group">
					<label>Tên người nhận hàng</label>
					<input type="text" name="name" class="form-control">
				</div>
				<div class="form-group">
					<label>Địa chỉ nhận hàng</label>
					<input type="text" name="name" class="form-control">
				</div>
				<div class="form-group">
					<label>Số điện thoại</label>
					<input type="text" name="name" class="form-control">
				</div>
				<div class="clearfix">
					<div class="pull-left">
						<div class="form-group">
							<label>Tỉnh/TP</label>
							<select class="form-control">
								<option>Ha noi</option>
								<option>Ho chi minh</option>
							</select>
						</div>
					</div>
					<div class="pull-left">
						<label>Quận huyện</label>
							<select class="form-control">
								<option>Ha noi</option>
								<option>Ho chi minh</option>
							</select>
					</div>
				</div>
				<div class="form-group">
					<label>Địa chỉ</label>
					<input type="text" name="" class="form-control">
				</div>
				<div class="form-group text-right">
					<button class="btn btn-primary">Lưu</button>
				</div>
			</form>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				var menu = $('.user-info').attr('data-menu');
				$('.user-page-menu li:nth-child('+menu+')').addClass('holder');
			})
		</script>
	</div>
@endsection
