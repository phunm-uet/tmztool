@extends('layouts.master')
@section('breadcrumb')
    Config
@endsection

@section('menu')
    <li class="treeview">
        <a href="../marketing">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>
    <li class="treeview">
      <a href="{{ route("import_fb") }}">
        <i class="fa fa-upload"></i> <span>Tạo collection SP Dropshing</span>
      </a>
    </li>
    <li class="treeview">
      <a href="{{ route("ads") }}">
        <i class="fa fa-gear"></i> <span>Tạp Campaign Dropship</span>
      </a>
    </li>
    <li class="treeview">
      <a href="{{ route("pages") }}">
        <i class="fa fa-gear"></i> <span>Quản lý page</span>
      </a>
    </li>

    @if ( Auth::user()->department->slug == "admin")
      <li>
        <li class="header">Admin</li>
      </li>    
      <li class="treeview">
        <a href="{{route('admin-home')}}">
          <i class="fa fa-gear"></i> <span>Chuyển qua Admin</span>
        </a>
      </li>    
    @endif    
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
					@if(Session::has("access_token"))
						<input type="text" class="form-control" name="accessToken" value="{{Session::get('access_token')}}">
					@else
						<input type="text" class="form-control" name="accessToken">
					@endif
					
				</div>
				<button class="btn btn-primary btn-block" type="submit">Set Config</button>			
			</form>
		</div>
	</div>
</div>
@stop