@extends('user.index')
@section('user-page')
	<div class="change-password" data-menu='5'>
		<form class="form-horizontal" role="form">
			<div class="form-group">
				<label class="control-label col-sm-2">Password hiện tại</label>
				<div class="col-sm-10">
					<input class="form-control col-sm-10" type="password" name="" >
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">Password mới</label>
				<div class="col-sm-10">
					<input class="form-control col-sm-10" type="password" name="" >
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">Nhập lại</label>
				<div class="col-sm-10">
					<input class="form-control col-sm-10" type="password" name="" >
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 col-sm-offset-2">
					<input type="submit" class="btn btn-primary form-control col-sm-2" value="Submit">
				</div>
				<div class="col-sm-2">
					<input type="reset" class="btn btn-danger form-control col-sm-2" value="Cancel">
				</div>
			</div>
		</form>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			var menu = $('.change-password').attr('data-menu');
			$('.user-page-menu li:nth-child('+menu+')').addClass('holder');
		})
	</script>
@endsection
