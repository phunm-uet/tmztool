@extends('layouts.master')
@section('menu')
    <li class="active treeview">
        <a href="javascript:void(0)">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>
    <li class="treeview">
      <a href="{{route('interest-home')}}">
        <i class="fa fa-upload"></i> <span>Quản lý interest</span>
      </a>
    </li>
    <li class="treeview">
      <a href="{{route('niche-home')}}">
        <i class="fa fa-gear"></i> <span>Quản lý niche</span>
      </a>
    </li>
    <li class="treeview">
      <a href="{{route('page-home')}}">
        <i class="fa fa-gear"></i> <span>Quản lý Page</span>
      </a>
    </li> 
    <li class="treeview">
      <a href="{{route('admin-config')}}">
        <i class="fa fa-gear"></i> <span>Cài đặt hệ thống</span>
      </a>
    </li>
    <li>
      <li class="header">Marketing</li>
    </li>
    <li class="treeview">
      <a href="{{route('marketing-home')}}">
        <i class="fa fa-gear"></i> <span>Chuyển qua Marketing</span>
      </a>
    </li>          
@stop