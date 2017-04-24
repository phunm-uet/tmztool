@extends('layouts.master')
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css"/>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.3/angular.min.js"></script>
@stop
@section('breadcrumb')
    Config
@endsection

@section('menu')
    <li class="treeview">
        <a href="./">
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
    <li class="treeview active">
      <a href="{{ route("pages") }}">
        <i class="fa fa-gear"></i> <span>Quản lý page</span>
      </a>
    </li>
@stop

@section('content')
<div class="container-fluid" ng-app="pages">
  <div class="row">
<div class="box" ng-controller="pagesController">
<div class="overlay" ng-if="loading">
  <i class="fa fa-spinner"></i>
</div>

  <div class="box-body">
    <table id="table-pages" class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th></th>
                <th>Tên Page</th>
                <th>Số Like</th>
                <th>URL</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
        <tr ng-repeat="page in pages track by $index">
          <td>@{{$index + 1}}</td>
          <td><img ng-src=@{{page.picture.data.url}} alt=""></td>
          <td>@{{page.name}}</td>
          <td>@{{page.fan_count}}</td>
          <td><a ng-href=@{{page.link}} target="_blank">@{{page.link}}</a></td>
          <td><a href="page/@{{page.id}}" class="btn btn-info btn-xs">Detail</a></td>
        </tr>
        </tbody>
        <tfoot>
            <tr>
                <th>STT</th>
                <th></th>
                <th>Tên Page</th>
                <th>Số Like</th>
                <th>URL</th>
                <th>Detail</th>
            </tr>
        </tfoot>
    </table>     
  </div>
</div>    
  </div>
</div>


@stop

@section('script')
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-datatables/0.6.1/angular-datatables.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-sanitize.js"></script>

<script>
 var app = angular.module('pages',[]);
 app.controller('pagesController', function($scope,$http){
  $scope.loading = true;
    var api = "http://tmztool.com/api/marketing/pages";
    $http.get(api).then(function(response){
        $scope.pages = response.data;
        $scope.loading = false;
         angular.element(document).ready( function (){
                 dTable = $('#table-pages')
                 dTable.DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": true                
                 });
         });
    }).catch(function(err){
      $scope.loading = false;
      alert("Da xay ra loi trong qua trinh load trang");
    });
 });

</script>
@stop
