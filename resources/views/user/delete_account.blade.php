@extends('user.index')
@section('user-page')
	<div class="delete-account" data-menu='6'>
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
		<form class="form-horizontal" role="form" method="POST" action="{{route('user.inactive')}}">
			{{ csrf_field() }}
			<div class="form-group">
				<label class="control-label col-sm-2">Email</label>
				<span class="col-sm-10">{{$email}}</span>

			</div>
			<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
				<label class="control-label col-sm-2">Password</label>
				<div class="col-sm-10">
					<input class="form-control col-sm-10" type="password" name="password" id="password" required>
					@if ($errors->has('password'))
						<span class="help-block">
							<strong>{{ $errors->first('password') }}</strong>
						</span>
					@endif
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">Lý do</label>
				<div class="col-sm-10">
					<textarea class="form-control col-sm-10" type="text"></textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
			        <div class="checkbox">
			        	<label >
			          		<input type="checkbox" id="confirm" id="confirm" required> Check xac nhận ok
							@if ($errors->has('confirm'))
								<span class="help-block">
									<strong>{{ $errors->first('confirm') }}</strong>
								</span>
							@endif

			        	</label>
			      	</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2 col-sm-offset-2">
					<input type="submit" class="btn btn-primary form-control col-sm-2" value="Lưu">
				</div>
			</div>
		</form>
	</div>

	<script type="text/javascript">
		$(document).ready(function(){
			var menu = $('.delete-account').attr('data-menu');
			$('.user-page-menu li:nth-child('+menu+')').addClass('holder');
		})
	</script>
@endsection
