@extends('layouts.master')
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