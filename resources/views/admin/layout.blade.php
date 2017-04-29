@extends('layouts.master')
@section('menu')
    <li class="active treeview">
        <a href="javascript:void(0)">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>
    <li class="treeview">
      <a href="interest">
        <i class="fa fa-upload"></i> <span>Quản lý interest</span>
      </a>
    </li>
    <li class="treeview">
      <a href="niche">
        <i class="fa fa-gear"></i> <span>Quản lý niche</span>
      </a>
    </li>
    <li class="treeview">
      <a href="page">
        <i class="fa fa-gear"></i> <span>Quản lý Page</span>
      </a>
    </li> 
    <li class="treeview">
      <a href="config">
        <i class="fa fa-gear"></i> <span>Cài đặt hệ thống</span>
      </a>
    </li>       
@stop