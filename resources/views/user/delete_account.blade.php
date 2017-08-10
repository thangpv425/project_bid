@extends('user.index')
@section('user-page')
	<div class="delete-account" data-menu='6'>
		<form class="form-horizontal" role="form">
			<div class="form-group">
				<label class="control-label col-sm-2">Email</label>
				<div class="col-sm-10">
					<input class="form-control col-sm-10" type="email" name="" >
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">Password</label>
				<div class="col-sm-10">
					<input class="form-control col-sm-10" type="password" name="" >
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
			        	<label>
			          		<input type="checkbox"> Check xac nhận ok
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
