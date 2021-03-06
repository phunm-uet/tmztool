@extends('layouts.master')
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.15/datatables.min.css"/>
<link rel="stylesheet" href="https://unpkg.com/angular-toastr/dist/angular-toastr.css" />  
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.3/angular.min.js"></script>
<style>
  .blue{
    background-color: rgba(151,187,205,1);
    width: 10px;
    height: 10px;
    display: inline-block;
  }

  .grey{
    background-color: rgba(220,220,220,1);
    width: 10px;
    height: 10px;
    display: inline-block;
  } 

  .note {
    margin-bottom: 10px;
  }
</style>
@stop
@section('breadcrumb')
   {{$name}}
@endsection

@section('menu')
    <li class="treeview">
        <a href="../../marketing">
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
<div class="container-fluid" ng-app="page-detail" ng-init="chart_class='col-xs-4'">
<div class="row" style="margin-bottom: 20px;">
    <div class="col-xs-8 col-xs-offset-4" ng-controller="changeTime">
        <div class="btn-group pull-right">
          <button type="button" class="btn btn-default period" ng-click="change7days()">Last 7 days</button>
          <button type="button" class="btn btn-default period" ng-click="change30days()">Last 30 days</button>
        </div>
    </div>
</div>
<input type="text" ng-init="id = {{$id}}" ng-model="id" hidden="true">
<div class="row">
  <div ng-class="chart_class" ng-controller="likes">
    <div class="panel panel-default">
      <div class="panel-heading">Page Likes</div>
      <div class="panel-body">
      <div class="note">
        <div class="blue"></div>  Tuần này
        <div class="grey"></div>  Tuần trước
      </div>
          <canvas id="line" class="chart chart-line" chart-data="data"
            chart-labels="labels" chart-series="series" chart-options="options"
            chart-dataset-override="datasetOverride" chart-click="onClick">
          </canvas>
      </div>
    </div>
</div>
  <div ng-class="chart_class" ng-controller="reach">
    <div class="panel panel-default">
      <div class="panel-heading">Page Reach</div>
      <div class="panel-body">
      <div class="note">
        <div class="blue"></div>  Tuần này
        <div class="grey"></div>  Tuần trước
      </div>      
          <canvas id="line" class="chart chart-line" chart-data="data"
            chart-labels="labels" chart-series="series" chart-options="options"
            chart-dataset-override="datasetOverride" chart-click="onClick">
          </canvas>
      </div>
    </div> 
</div>

  <div ng-class="chart_class" ng-controller="engagement">
    <div class="panel panel-default">
      <div class="panel-heading">Page Engagement</div>
      <div class="panel-body">
      <div class="note">
        <div class="blue"></div>  Tuần này
        <div class="grey"></div>  Tuần trước
      </div>      
          <canvas id="line" class="chart chart-line" chart-data="data"
            chart-labels="labels" chart-series="series" chart-options="options"
            chart-dataset-override="datasetOverride" chart-click="onClick">
          </canvas>
      </div>
    </div> 
</div>

</div>
<div class="row">
  <div class="box box-primary" ng-controller="top-posts">
    <div class="box-header">
      <h3>10 Recent Post</h3>
    </div>
    <div class="box-body col-xs-6">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>STT</th>
              <th>Link</th>
              <th>Like</th>
              <th>Reach</th>
              <th>Comment</th>
              <th>Share</th>
              <th>Create At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="post in posts track by $index" ng-if="$index % 2 == 0">
              <td>@{{$index+1}}</td>
              <td><a ng-href="https://facebook.com/@{{post.id}}" target="_blank"><img ng-src="@{{post.picture}}" style="width:50px;height: 50px;" /></a></td>
              <td>@{{post.reactions.summary.total_count}}</td>
              <td>@{{post.insights.data["0"].values["0"].value}}</td>
              <td>@{{post.comments.summary.total_count}}</td>
              <td>@{{post.shares.count || 0}}</td>
              <td>@{{post.created_time | date:"dd-MM-yy" }}</td>
              <td>
              <a href="javascript:void(0)" data-toggle="tooltip" title="Create Campaign" ng-click="openModal(post.id)" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"> </i></a>    
              </td>
            </tr>
          </tbody>
        </table>      
    </div>
    <div class="box-body col-xs-6">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>STT</th>
              <th>Link</th>
              <th>Like</th>
              <th>Reach</th>
              <th>Comment</th>
              <th>Share</th>
              <th>Create At</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="post in posts track by $index" ng-if="$index % 2 == 1">
              <td>@{{$index + 1}}</td>
              <td><a ng-href="https://facebook.com/@{{post.id}}" target="_blank"><img ng-src="@{{post.picture}}" style="width:50px;height: 50px;" /></a></td>
              <td>@{{post.reactions.summary.total_count}}</td>
              <td>@{{post.insights.data["0"].values["0"].value}}</td>
              <td>@{{post.comments.summary.total_count}}</td>
              <td>@{{post.shares.count || 0}}</td>
              <td>@{{post.created_time | date:"dd-MM-yy"  }}</td>
              <td>    
              <a href="javascript:void(0)" data-toggle="tooltip" title="Create Campaign" ng-click="openModal(post.id)" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"> </i></a>        
            </tr>
          </tbody>
        </table>      
    </div>
      <div class="box-footer" style="text-align: center;"> 
      </div>
    <div class="modal fade" id="campaign">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Tạo quảng cáo </h4>
          </div>
          <div class="modal-body">
            <form ng-submit="taocampaign()" method="POST">
              <div class="row">
              <div class="col-xs-12">
                <div class="form-group">
                    <label>Ten Campaign</label>
                    <input type="text" class="form-control" required="true" ng-model="name_campaign">
                </div>                
              </div>
              </div>

              <div class="row">
                  <div class="col-lg-5">
                        <div class="form-group input-group">
                          <div class="input-group-btn">
                            <label class="btn bg-olive btn-flat">Niche</label>
                          </div>
                          <select name="niche" class="form-control" required="true" ng-options="niche as niche.name for niche in niches" ng-model="selectedNiche" ng-change="changeNiche(selectedNiche)"></select>
                        </div>
                  </div>
                  <div class="col-lg-7">
                    <div class="form-group input-group">
                            <div class="input-group-btn">
                              <label class="btn bg-olive btn-flat">Country</label>
                            </div>
                  <select name="location" class="form-control" data-placeholder="Select Country" ng-model="country" id="country" style="width: 100%">
                  </select>
                    </div>                 
                  </div>
              </div>
              {{-- Niche and Country --}}

              {{-- Gioi tinh do tuoi --}}
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
              {{-- End gioi tinh do tuoi --}}

              {{-- Tk Ads va Pixel --}}
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group input-group">
                    <div class="input-group-btn">
                      <label class="btn bg-olive btn-flat">TK ADS</label>
                    </div>
                    <select class="form-control" required="true" ng-options="adaccount as adaccount.name for adaccount in adaccounts" ng-model="selectedAdAccount" ng-change="changeAdAccount(selectedAdAccount)"></select>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group input-group">
                    <div class="input-group-btn">
                      <label class="btn bg-olive btn-flat">Pixels: </label>
                    </div>
                    <select class="form-control" ng-options="pixel.name + '(' + pixel.id +')' for pixel in pixels" ng-model="selectedPixel"></select>
                  </div>
                </div> 
              </div>
      
            {{-- Interest --}}
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group input-group" id="pageid">
                  <div class="input-group-btn">
                    <label class="btn bg-olive btn-flat">Interests: </label>
                  </div>
                  <select class="form-control select2" required="true" multiple="true" ng-options="interest.id as interest.name for interest in interests track by interest.id" ng-model="selectedInterest" style="width: 100%" id="interest"></select>
                </div>
              </div>              
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group input-group">
                  <label>Budget</label>
                  <input type="number" class="form-control" ng-model="budget" ng-init="budget=5" required="required">
                </div>                
              </div>
              <div class="col-lg-6">
                <div class="form-group input-group">
                  <label>PPE/WC</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="typeAds" ng-model="typeAds" value="POST_ENGAGEMENT">
                      PPE
                    </label>
                    <label>
                      <input type="radio" name="typeAds" ng-model="typeAds" value="CONVERSIONS">
                      WC
                    </label>                    
                  </div>
                </div>                
              </div>              
            </div>
              <button type="submit" class="btn btn-primary btn-block">Tao Campaign</button>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="box box-success" ng-controller="ads-running">
    <div class="box-header">
      <h3>Các campaign đang chạy ads</h3>
    </div>
    <div class="box-body col-xs-12">
      <table class="table table-hover table-responsive">
        <thead>
          <tr>
            <th></th>
            <th>Tên campaign</th>
            <th>Reaction</th>
            <th>Comment</th>
            <th>Share</th>
            <th>CPC</th>
            <th>Spent</th>
            <th>CPM</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="campaign in campaigns track by $index">
            <td><img ng-src="@{{campaign.image}}" style="width: 50px;height: 50px"></td>
            <td>@{{campaign.campaign_name}}</td>
            <td class="reaction">@{{campaign.reaction || '0'}}</td>
            <td class="comment">@{{campaign.comment || '0' }}</td>
            <td class="share">@{{campaign.share || '0'}}</td>
            <td class="cpc">@{{campaign.cpc || '...'}}</td>
            <td class="spent">@{{campaign.spent || '0'}}</td>
            <td class="cpm">@{{campaign.cpm || '...'}}</td>
            <td>
              <a href="javascript:void(0)" ng-click="pause(campaign.campaign_id)">Pause</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="box-footer">
      
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
  <script src="https://unpkg.com/angular-toastr/dist/angular-toastr.tpls.js"></script>
<script src="{{ asset('js/angular/angular-chart.min.js') }}"></script>
<script>
var app = angular.module("page-detail", ["chart.js","toastr"]);
</script>
<script src="{{ asset('js/angular/change-time.js')}}"></script>
<script src="{{ asset('js/angular/likes.js')}}"></script>
<script src="{{ asset('js/angular/reach.js')}}"></script>
<script src="{{ asset('js/angular/engagement.js')}}"></script>
<script src="{{ asset('js/angular/top-posts.js')}}"></script>
<script src="{{ asset('js/angular/ads-running.js')}}"></script>
<script>
  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip(); 
    $(".select2").select2();
    $("#country").select2({
      ajax : {
        url : "http://tmztool.com/api/marketing/country",
        placeholder : "Select country",
        dataType : 'json',
        delay : 250,
        data : function(params){
          return {
            q : params.term
          }
        },
        processResults : function(data){
          return {
            results : $.map(data, function(val, l) {
              return {id:val.country_code,text:val.name};
            })
          }
        },
      }
    });
  });
</script>
@stop