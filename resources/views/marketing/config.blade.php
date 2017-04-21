@extends('layouts.master')
@section('breadcrumb')
    Config
@endsection

@section('menu')
    <li class="treeview">
        <a href="marketing">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>
    <li class="treeview">
      <a href="{{ route("import_fb") }}">
        <i class="fa fa-upload"></i> <span>Import store FB</span>
      </a>
    </li>
    <li class="active treeview">
      <a href="{{ route("ads") }}">
        <i class="fa fa-gear"></i> <span>Auto Campaign</span>
      </a>
    </li>
@stop

@section('content')
<div class="row">
	<div class="col-xs-6 col-xs-offset-3">
		<div class="box box-info box-body">
			@if( Session::has('errToken') )
				<div class="alert alert-warning">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>Alert!</strong> Nhập token trước khi vào hệ thống...
				</div>			
			@endif
			@if ( count($errors) > 0)
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>Alert!</strong> AccessToken Invalid ...
				</div>
			@endif
			<div id="errors">
			</div>
			<form action="" method="POST">
				{{ csrf_field() }}
				<div class="form-group">
					<label for="accessToken">AccessToken For Ads</label>
					@if(Session::has("accessToken"))
						<input type="text" class="form-control" name="accessToken" value="{{Session::get('accessToken')}}">
					@else
						<input type="text" class="form-control" name="accessToken">
					@endif
					
				</div>
				<div class="form-group">
					<label for="accessToken">AccessToken For Product Import</label>
					@if(Session::has("accessToken"))
						<input type="text" class="form-control" name="accessTokenProduct" value="{{Session::get('accessTokenProduct')}}">
					@else
						<input type="text" class="form-control" name="accessTokenProduct">
					@endif
				</div>
				<button class="btn btn-primary btn-block" type="submit">Set Config</button>			
			</form>
		</div>
	</div>
</div>
@stop