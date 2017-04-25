@extends('admin.layout')

@section('content')

<div class="container-fluid">
	<div class="col-xs-8">
		<div class="panel panel-default">
			<div class="panel-heading">Cập nhật Interest</div>
			<div class="panel-body">
				@if(Session::has('success'))
					<div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong>Success!</strong> Cập nhật thành công
					</div>
				@elseif(Session::has('fail'))
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<strong>Fail!</strong> Đã xảy ra lỗi
					</div>
				@endif
				<form action="update" id="form" method="POST">
					{{ csrf_field() }}
					<input type="text" value="{{Request::get('id')}}" name="id" hidden="true">
					<div class="form-group">
						<label>Tên Interest</label>
						<input type="text" name="name" class="form-control" required="required" value="{{$interest->name}}">
					</div>
					<div class="form">
						<label>Lượng Interest</label>
						<input type="text" name="num_audience" class="form-control" required="required" value="{{$interest->num_audience}}">
					</div>
					<div class="form-group">
						<label>Niches</label>
						<select class="form-control" name="niche_id">
							@foreach($niches as $niche)
								@if($niche->id == $interest->niche->id)
									<option selected="true" value="{{$niche->id}}">{{$niche->name}}</option>
								@else
									<option value="{{$niche->id}}">{{$niche->name}}</option>
								@endif
								
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label>Nhập Interest</label>
						<textarea name="targeting" id="targeting" class="form-control" rows="5" required="required">{{$interest->targeting}}</textarea>
					</div>
					<button type="submit" class="btn btn-primary btn-block" id="submit_interest">Update Interest</button>
				</form>				
			</div>
		</div>

	</div>
</div>


@stop
@section('script')

@stop