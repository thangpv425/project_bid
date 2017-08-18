@extends('user.index')
@section('user-page')
	<div class="change-password" data-menu='5'>
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
		<form class="form-horizontal" role="form" method="POST" action="{{route('user.change-password')}}">
			{{ csrf_field() }}
			<div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
				<label class="control-label col-sm-2">Password hiện tại</label>
				<div class="col-sm-10">
					<input class="form-control col-sm-10" type="password" name="current_password" required>
					@if ($errors->has('current_password'))
						<span class="help-block">
                                        <strong>{{ $errors->first('current_password') }}</strong>
                                    </span>
					@endif
				</div>
			</div>

			<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
				<label class="control-label col-sm-2">Password mới</label>
				<div class="col-sm-10">
					<input class="form-control col-sm-10" id = "password" type="password" name="password" required>
					@if ($errors->has('password'))
						<span class="help-block">
							<strong>{{ $errors->first('password') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">Nhập lại</label>
				<div class="col-sm-10">
					<input class="form-control col-sm-10" id = "password-confirm" type="password" name="password_confirmation" required>
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
