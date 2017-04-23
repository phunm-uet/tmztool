@extends('layouts.master')

@section('css')
<style>
</style>
@endsection

@section('breadcrumb')
    Dashboard
@endsection

@section('menu')
    <li class="active treeview">
        <a href="javascript:void(0)">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>
    <li class="treeview">
      <a href="{{ route("import_fb") }}">
        <i class="fa fa-upload"></i> <span>Quản lý interest</span>
      </a>
    </li>
    <li class="treeview">
      <a href="{{ route("ads") }}">
        <i class="fa fa-gear"></i> <span>Quản lý niche</span>
      </a>
    </li>
@stop

@section('content')
	<div class="container">
		<div class="col-xs-8 col-xs-offset-2">
			<div class="row">
				<div class="col-md-3 col-xs-12 el-tool">
					<a href="/" style="font-size: 18px"><img src="https://www.awicons.com/free-icons/download/toolbar-icons/vista-stock-icons-by-lokas-software/png/256/add.png" style="width: 100%">Thêm Interest</a>
				</div>
				<div class="col-md-3 col-xs-12 el-tool">
					<a href="{{ route("ads") }}" style="font-size: 18px"><img src="http://pragmatictestlabs.com/wp-content/uploads/2016/09/Services_blue.png" style="width: 100%">Tool Auto Campaign FB</a>
				</div>
				<div class="col-md-3 col-xs-12 el-tool">
					<a href="{{ route("config") }}" style="font-size: 18px"><img src="http://findicons.com/files/icons/986/aeon/128/settings.png" style="width: 100%">Config</a>
				</div>								
			</div>

		</div>
	</div>
@stop