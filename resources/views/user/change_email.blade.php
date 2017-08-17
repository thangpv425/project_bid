@extends('user.index')
@section('user-page')
	<div class="change-email" data-menu='4'>
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
		<form class="form-horizontal" role="form" method="POST" action="{{route('user.change-email')}}">
			{{ csrf_field() }}
			<div class="form-group{{ $errors->has('current_email') ? ' has-error' : '' }}">
				<label class="control-label col-sm-2">Mail hiện tại</label>
				<div class="col-sm-10">
					<input class="form-control col-sm-10" type="email" name="current_email" id="current_email" required>
					@if ($errors->has('current_email'))
						<span class="help-block">
							<strong>{{ $errors->first('current_email') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
				<label class="control-label col-sm-2">Mail mới</label>
				<div class="col-sm-10">
					<input class="form-control col-sm-10" type="email" name="email" id="email" required>
					@if ($errors->has('email'))
						<span class="help-block">
							<strong>{{ $errors->first('email') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">Nhập lại</label>
				<div class="col-sm-10">
					<input class="form-control col-sm-10" type="email" name="email_confirmation" id="email-confirm" required>
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
			var menu = $('.change-email').attr('data-menu');
			$('.user-page-menu li:nth-child('+menu+')').addClass('holder');
		})
	</script>
@endsection
