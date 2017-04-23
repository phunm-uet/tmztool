@extends('layouts.master')
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css"/>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.3/angular.min.js"></script>
@stop
@section('breadcrumb')
    Page Detail
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
<div class="container-fluid" ng-app="page-detail">
<input type="text" ng-init="id = {{$id}}" ng-model="id" hidden="true">
<div class="row">
  <div class="col-lg-4 col-sm-12" ng-controller="likes">
    <div class="panel panel-default">
      <div class="panel-heading">Page Likes</div>
      <div class="panel-body">
          <canvas id="line" class="chart chart-line" chart-data="data"
            chart-labels="labels" chart-series="series" chart-options="options"
            chart-dataset-override="datasetOverride" chart-click="onClick">
          </canvas
      </div>
    </div>
  </div>  
</div>
  <div class="col-lg-4 col-sm-12" ng-controller="reach">
    <div class="panel panel-default">
      <div class="panel-heading">Page Reach</div>
      <div class="panel-body">
          <canvas id="line" class="chart chart-line" chart-data="data"
            chart-labels="labels" chart-series="series" chart-options="options"
            chart-dataset-override="datasetOverride" chart-click="onClick">
          </canvas
      </div>
    </div>
  </div>  
</div>

  <div class="col-lg-4 col-sm-12" ng-controller="engagement">
    <div class="panel panel-default">
      <div class="panel-heading">Page Engagement</div>
      <div class="panel-body">
          <canvas id="line" class="chart chart-line" chart-data="data"
            chart-labels="labels" chart-series="series" chart-options="options"
            chart-dataset-override="datasetOverride" chart-click="onClick">
          </canvas
      </div>
    </div>
  </div>  
</div>

</div>
<div class="row">
  <div class="box box-primary" ng-controller="top-posts">
    <div class="box-header">
      <h3>10 Recent Post</h3>
    </div>
    <div class="box-body">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>STT</th>
              <th></th>
              <th>Link</th>
              <th>Like</th>
              <th>Reach</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="post in posts track by $index">
              <td>@{{$index + 1}}</td>
              <td><img ng-src="@{{post.picture}}" style="width:50px" /></td>
              <td><a ng-href="https://facebook.com/@{{post.id}}" target="_blank">https://facebook.com/@{{post.id}}</a></td>
              <td>@{{post.reactions.summary.total_count}}</td>
              <td>@{{post.insights.data["0"].values["0"].value}}</td>
              <td><button type="button" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"> </i>Campaign</button></td>
            </tr>
          </tbody>
        </table>      
    </div>

    <div class="box-footer" style="text-align: center;">
      <button type="button" class="btn btn-info">View More</button>
    </div>
  </div>
</div>

<div class="modal fade" id="campaign">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Tạo quảng cáo </h4>
      </div>
      <div class="modal-body">
        <form action="">
          <div class="row">
              <div class="col-lg-5">
                    <div class="form-group input-group" id="niche">
                      <div class="input-group-btn">
                        <label class="btn bg-olive btn-flat">Niche</label>
                      </div>
                      <select name="niche" class="form-control" required="true" ng-options="niche as niche.name for niche in niches" ng-model="selectedNiche" ng-change="changeNiche(selectedNiche)"></select>
                    </div>
              </div>            
          </div>
                <div class="form-group">
                  <div class="row">
                      <div class="col-xs-3" id="minage">
                        <label>Min Age: </label>
                        <input type="text" required="true" class="form-control" ng-model="minage" ng-init="minage=21" placeholder="21">
                      </div>
                      <div class="col-xs-3" id="maxage">
                        <label>Max Age: </label>
                        <input type="text" name="maxage" required="true" class="form-control" ng-init="maxage=65" ng-model="maxage" placeholder="65">
                      </div>
                      <div class="col-xs-6">
                        <label for="">Gender: </label><br>
                        <input type="radio" name="gender" ng-model="gender" value="1" class="flat-red"><label>Nam</label>
                        <input type="radio" name="gender" ng-model="gender" value="2" class="flat-red"><label>Nữ</label>
                        <input type="radio" name="gender" ng-model="gender" value="1,2" class="flat-red" checked><label>All</label>
                      </div>                  
                  </div>
                </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

@stop
@section('script')
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.0/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-datatables/0.6.1/angular-datatables.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-sanitize.js"></script>
<script src="{{ asset('js/angular/angular-chart.min.js') }}"></script>
<script>
var app = angular.module("page-detail", ["chart.js"]);
</script>
<script src="{{ asset('js/angular/likes.js')}}"></script>
<script src="{{ asset('js/angular/reach.js')}}"></script>
<script src="{{ asset('js/angular/engagement.js')}}"></script>
<script src="{{ asset('js/angular/top-posts.js')}}"></script>
@stop