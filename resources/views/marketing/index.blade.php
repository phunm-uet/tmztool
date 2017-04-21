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
        <i class="fa fa-upload"></i> <span>Import store FB</span>
      </a>
    </li>
    <li class="treeview">
      <a href="{{ route("ads") }}">
        <i class="fa fa-gear"></i> <span>Auto Campaign</span>
      </a>
    </li>
@stop

@section('content')
	<div class="container">
		<div class="col-xs-8 col-xs-offset-2">
			<div class="row">
				<div class="col-md-3 col-xs-12 el-tool">
					<a href="{{ route("import_fb") }}" style="font-size: 18px"><img src="http://www.iconshock.com/img_vista/MATERIAL/text/jpg/import_icon.jpg" style="width: 100%">Tool import Store FB</a>
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