@extends('admin.layout')
@section('css')
	
@stop

@section('content')
	<div class="container-fluid">
		<div class="col-xs-12">
			@if ( Session::has("error") )
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>{{ Session::get('error') }}</strong>
				</div>
			@endif			
		</div>

		<div class="col-xs-4">
			<div class="box">
				<div class="box-body">
					<form action="" method="POST" role="form">
					{{ csrf_field() }}		
						<div class="form-group">
							<label for="">Cookie</label>
							<textarea name="cookie" id="inputCookie" class="form-control" rows="5" required="required">{{ Session::get('cookie_fb' )}}</textarea>
						</div>
						<button type="submit" class="btn btn-primary btn-block">Add</button>
					</form>					
				</div>
			</div>
		</div>
		<div class="col-xs-8">
			<div class="box">
				<div class="overlay" hidden="hidden">
				  <i class="fa fa-spinner fa-spin"></i>
				</div>			
				<div class="box-body">
					<table class="table table-hover table-responsive">
						<thead>
							<tr>
								<th>STT</th>
								<th>User ID</th>
								<th>Cookie</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($userSystems as $user)
								<tr>
									<td>{{$loop->index + 1}}</td>
									<td>{{ $user->user_id}}</td>
									<td>{{ str_limit($user->cookie,80,"...") }}</td>
									<td>
										<a href="javascript:void(0)" class="btn btn-xs btn-info" onclick="get_token('{{$user->user_id}}')">Get Token</a>
										<a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="deleteUser('{{$user->user_id}}')">Delete</a>
									</td>
								</tr>
							@endforeach

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@stop
@section('script')
	<script>
		var get_token = function(user_id){
			$('.overlay').show();
			var token = '{{ csrf_token() }}';
			$.post('config/get-token', {user_id: user_id,_token : token}, function(data, textStatus, xhr) {
				if(data.success == 1)
				{
					$('.overlay').hide();
					alert("Done");
				}
			});
		};

		var deleteUser = function(user_id)
		{
			var a = confirm("Bạn có chắc muốn xóa");
			if(a){
				$.post('config/delete-user', {user_id: user_id,_token :  window.Laravel.csrfToken}, function(data, textStatus, xhr) {
					if(data.success == 1)
					{
						location.reload();
					}
				});				
			}
		}
	</script>
@endsection