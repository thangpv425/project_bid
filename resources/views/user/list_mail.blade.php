@extends('user.index')
@section('user-page')
	<div class="list-mail" data-menu='7'>
			<div class="panel-group" id="accordion">
				@for($i=1; $i<6; $i++)
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$i}}">
									mail subject
								</a>
							</h4>
						</div>
						<div id="collapse{{$i}}" class="panel-collapse collapse in">
							<div class="panel-body">
								mail body
							</div>
						</div>
					</div>
				@endfor
			</div>

		<script type="text/javascript">
			$(document).ready(function(){
				var menu = $('.list-mail').attr('data-menu');
				$('.user-page-menu li:nth-child('+menu+')').addClass('holder');
				$('.collapse').collapse()
			})
		</script>
	</div>
@endsection
