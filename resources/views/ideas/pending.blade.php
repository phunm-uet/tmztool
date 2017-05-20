@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.3/angular.min.js"></script>
   <style>
       .wysihtml5-sandbox {
        height: 74px;
       }
   </style>
@endsection

@section('breadcrumb')
    Idea Pending
@endsection

@section('menu')
    <li class="treeview">
        <a href="{{ url('/idea') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>
    <li class="treeview">
      <a href="{{ url('/idea/form') }}">
        <i class="fa fa-edit"></i> <span>Form Idea</span>
      </a>
    </li>
    <li class="active treeview">
      <a href="{{ url('/idea/pending') }}">
        <i class="fa fa-share"></i> <span>Idea Pending</span>
      </a>
    </li>
@endsection


@section('content')
    <div class="box" ng-app="pendingApp" ng-controller="pendingCtrl">
        <div class="box-header">
            <h3 class="box-title">Total Idea Pending</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table id="table-ideas-pending" class="table table-bordered table-striped">
                <thead>
                    <tr >
                        <th ng-repeat="header in headers">@{{ header }}</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                        <tr ng-repeat="idea in ideas track by $index">
                            <td class="idIdea"> @{{ $index+1 }}</td>
                            <td class="nameIdea">@{{idea.name }}</td>
                            <td class="description" ng-bind-html="idea.description"></td>
                            <td class="niche">@{{idea.niche1.name }}</td>
                            <td><span class="label label-warning">Pending</span></td>
                            <td>@{{ idea.created_at }}</td>
                            <td>
                            <a href="javascript:void(0)"  ng-click="openModal($index)" class="btn btn-xs btn-primary">Edit</a>
                            <a href="javascript:void(0)"  ng-click="deleteIdea($index)" class="btn btn-xs btn-danger">Delete</a>                            
                            </td>
                        </tr>

                </tbody>
                <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Niche</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->


        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" style="word-wrap: break-word;">
                    <!-- Form -->
                    <form ng-submit="editIdea()" name="form-edit">
                        {{ csrf_field() }}
                        <div class="modal-body row">
                            <div class="col-md-6">
                                <img ng-src="@{{selectedIdea.link}}" id="preview" alt="preview" style='height: 70%; width: 70%; object-fit: contain'> 
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="description" class="form-control" rows="3" id="description" name="description" ng-model="selectedIdea.description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="source1">Source 1</label>
                                    <select name="source1" class="form-control" ng-model="selectedIdea.source_id">
                                        <option ng-repeat="source in sources" ng-if="source.level == 1" value="@{{source.id}}" ng-selected="source.id == selectedIdea.source_id">@{{source.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="source2">Source 2</label>
                                    <select name="source2" class="form-control" ng-model="selectedIdea.source_id_2">
                                        <option ng-repeat="source in sources" ng-if="source.level == 2" value="@{{source.id}}" ng-selected="source.id == selectedIdea.source_id_2">@{{source.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="source3">Source 3</label>
                                    <select name="source3" class="form-control" ng-model="selectedIdea.source_id_3">
                                        <option ng-repeat="source in sources" ng-if="source.level == 3" value="@{{source.id}}" ng-selected="source.id == selectedIdea.source_id_3">@{{source.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" id="idIdea" name="id" class="id" value=@{{selectedIdea.id}} >
                                <div class="form-group">
                                    <label for="nameIdea">Name Idea</label>
                                    <input type="text" class="form-control" id="nameIdea" name="nameIdea" ng-model="selectedIdea.name">
                                </div>
                                
                                <div class="form-group">
                                    <label for="audience">Audience</label>
                                    <input type="text" class="form-control" id="audience" name="audience" ng-model="selectedIdea.audience">
                                </div>
                                <div class="form-group">
                                    <label for="link">Link</label>
                                    <input type="text" class="form-control" id="link" class="link" name="link" ng-model="selectedIdea.link">
                                </div>
                                <div class="form-group">
                                    <label for="reason">Reason</label>
                                    <textarea class="form-control" rows="3" id="reason" name="reason" ng-model="selectedIdea.reason"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="deploy">Deploy Description</label>
                                    <textarea id="deploy" class="form-control" rows="3" id="deploy" name="deploy_description" ng-model="selectedIdea.deploy_description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="niche1">Niche 1</label>
                                    <select name="niche1" class="form-control" ng-model="selectedIdea.niche_id">
                                        <option ng-repeat="niche in niches" value="@{{niche.id}}" ng-if="niche.level == 1" ng-selected="niche.id == selectedIdea.niche_id">@{{niche.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="niche2">Niche 2</label>
                                    <select name="niche2" class="form-control" ng-model="selectedIdea.niche_id_2">
                                        <option ng-repeat="niche in niches" value="@{{niche.id}}" ng-if="niche.level == 2" ng-selected="niche.id == selectedIdea.niche_id_2">@{{niche.name}}</option>
                                    </select>
                                </div>
                                
                            </div>
                             
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-datatables/0.6.1/angular-datatables.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-sanitize.js"></script>
<script>
    $(function () {       
        // set timeout hide alert status
        $('body').click(function() {
            setTimeout(function(){ 
                $('.alert').hide();
            }, 3000);
        });

        $('#modal').on('shown.bs.modal', function () {
           $('#description').wysihtml5({
              toolbar: {
                "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
                "emphasis": true, //Italics, bold, etc. Default true
                "lists": false, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
                "html": false, //Button which allows you to edit the generated HTML. Default false
                "link": true, //Button to insert a link. Default true
                "image": false, //Button to insert an image. Default true,
                "color": false, //Button to change color of font  
                "blockquote": false, //Blockquote  
                "size": 'sm' //default: none, other options are xs, sm, lg
              }
            });
           $('#deploy').wysihtml5({
              toolbar: {
                "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
                "emphasis": true, //Italics, bold, etc. Default true
                "lists": false, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
                "html": false, //Button which allows you to edit the generated HTML. Default false
                "link": true, //Button to insert a link. Default true
                "image": false, //Button to insert an image. Default true,
                "color": false, //Button to change color of font  
                "blockquote": false, //Blockquote  
                "size": 'sm' //default: none, other options are xs, sm, lg
              }
            });

        });

        $('#modal').on('hidden.bs.modal', function(){

            $('.wysihtml5-sandbox, .wysihtml5-toolbar').remove();
            $("body").removeClass("wysihtml5-supported");
            $('#description').show();
            $('#deploy').show();
        });
    });

</script>
    <script>
    var app = angular.module('pendingApp', ['ngSanitize']);
    // Constant API_BASE_URL plz replace when deploy
    app.constant('API', "http://tmztool.com/api");
    // Config header request to pass authentication api access
    var config = {headers:  {
        'Authorization': 'Bearer {{Auth::user()->api_token}}',
        'Accept': 'application/json',
        }
    };
    app.controller("pendingCtrl",function($scope,$http,API) {

        $http.get(API+"/ideas/pending",config).then(function(response){
            var data = response.data;
            console.log(data);
            $scope.ideas = data.ideas;
            // console.log($scope.ideas);
            $scope.niches = data.niches;
            $scope.niche = $scope.niches[0];
            $scope.sources = data.sources;
            $scope.source = $scope.sources[0];
            angular.element(document).ready( function () {
                // Config datatable
                 dTable = $('#table-ideas-pending')
                 dTable.DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": true                
                 });
             });  
        });
        // Show moddal edit Idea
        $scope.openModal = function(index){
            $scope.selectedIdea =  $scope.ideas[index];
            $("#modal").modal();
        }
        // Delete Idea when click buton delete
        $scope.deleteIdea = function(index){
            if(confirm('Are you sure?')) {
                $http.post(API+"/delete/idea",{
                    id: $scope.ideas[index].id,
                },config).then(function(response){
                    $scope.ideas.splice(index,1);
                })
            }
        }

        $scope.editIdea = function()
        {
            $scope.selectedIdea.description = $("#description").val();
            $scope.selectedIdea.deploy_description = $("#deploy").val();
            $http.post(API+"/update/idea",{
                data : $scope.selectedIdea
            },config).then(function(response){
                for(var i = 0 ; i < $scope.niches.length; i++)
                {
;                    if($scope.niches[i].id == $scope.selectedIdea.niche_id){
                        $scope.selectedIdea.niche1 = $scope.niches[i];
                        break;
                    }
                }
                $("#modal").modal('hide');
            })           
        }
        $scope.headers = ["Id","Name","Description","Niche","Status","Created at"]
      
        });
</script> 
@endsection