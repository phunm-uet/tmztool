@extends('layouts.master')
@section('breadcrumb')
    Import FB store
@endsection
@section('css')
	<style>
		.container {
			margin-top:50px;
		}
		#loading {
		   width: 100%;
		   height: 100%;
		   top: 0;
		   left: 0;
		   position: fixed;
		   display: block;
		   opacity: 0.7;
		   background-color: #fff;
		   z-index: 99;
		   text-align: center;
		}

		#loading-image {
		  position: absolute;
			margin: auto;
			margin-top : 200px;
		  z-index: 100;
		}		
	</style>
@endsection
@section('menu')
    <li class="treeview">
        <a href="./">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>
    <li class="treeview active">
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
@stop
@section('content')
<div  ng-app="tool" ng-controller="toolCtrl">
<div id="loading" ng-show="loading">
  <img id="loading-image" src="https://yudyx86.files.wordpress.com/2015/06/loading.gif" alt="Loading..." />
</div>
	<div class="container-fluid">
		<div class="col-xs-4">
				<p>Select your page</p>
				<select ng-options="page as page.name for page in pages" 
				   ng-model="item" ng-change="changePage(item)" class="form-control"></select>
		<div class="row" style="margin-top: 10px">
			<div class="col-xs-12" ng-if="selectPage">
				<p>Select Product Category</p>
				<select ng-options="category as category.name for category in prodcutCategory" 
				   ng-model="item" ng-change="chanegCategory(item)" class="form-control"></select>				
			</div>
			<div class="col-xs-12" ng-if="selectPage == false">
				<p>Nothing product Category</p>			
			</div>			
		</div>
		<div class="row" style="margin-top: 10px" ng-if="openSelectCollection">
			<div class="col-xs-12" ng-if="selectPage">
			<label for="store_id">Enter Store ID</label>
				<div class="form-inline">
					<div class="form-group">
					<input type="text" class="form-control" required="required" ng-model="store_id">
					<button type="button" id="get-collection" class="btn btn-primary" ng-click="getCollection(store_id)">Get Collection</button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<p>Select Product Collection</p>
					<div class="col-xs-8">
						<select ng-options="collection as collection.name for collection in collections" 
						   ng-model="selectedCollection" ng-change="changeCollection(selectedCollection)" class="form-control"></select>					
					</div>
					<div class="col-xs-4">
						<button type="button" class="btn btn-success" ng-click="openModal()">Create New Collection</button>
					</div>					
				</div>				
			</div>
		</div>			
		</div>
		<div class="col-xs-6 col-xs-offset-2">
			<div class="panel panel-primary">
				<div class="panel-heading">Page info</div>
				<div class="panel-body">
					<div class="contentPage row">
						<div class="col-xs-4">
							<img ng-src="@{{ selectedPage.picture.data.url}}" alt="" id="imgPage">
						</div>
						<div class="col-xs-8">
							<p id="namePage">@{{selectedPage.name}}</p>
						</div>						
					</div>
					<div class="row" ng-if="uploadSuccess">
						<p>Upload Succes: </p>
						<div class="products" >
							<p ng-repeat="product in products">
									<a href="https://www.facebook.com/@{{product.id}}" target="_blank">@{{product.name}}</a>
							</p>
						</div>						
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid" ng-if="openSubmitLinks">
		<form ng-submit="submitLink(links)">
			<div class="form-group">
				<label for="">Enter Link Shopify</label>
				<textarea class="form-control" rows="8" id="comment" ng-model="links" ng-list="&#10;" ng-trim="false"></textarea>
			</div>
			<button class="btn btn-block btn-success">Submit</button>
		</form>
	</div>

	{{-- Modal Create new Collection --}}
	<div class="modal fade" id="new-collection">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Create new Collection</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<input type="text" class="form-control" required="required" ng-model="collectionName">
					</div>
					<button type="button" class="form-control btn btn-primary" ng-click="creatNewCollection(store_id)">Add</button>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>	

</div>
@endsection

@section("script")
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.3/angular.min.js"></script>
<script>
	var app = angular.module("tool",[]);
	app.controller("toolCtrl",function($scope,$http){
		$scope.selectedPage = false;
		$scope.selecedCategory = false;
		$scope.loading = false;
		$scope.uploadSuccess = false;
		$scope.openSelectCollection = false;
		$scope.openSubmitLinks = false;
		$http.get("api/pages").then(function(response){
			 $scope.pages = response.data;
			 $scope.loading = false;
		})
		$scope.changePage = function(selectePage){
			$scope.openSelectCollection = true;
			$scope.selectedPage = selectePage;
			$scope.selectPage = true;
			$scope.loading = true;
			$http.get("api/product_category",{params:{"id": selectePage.id}}).then(function(response){
				$scope.loading = false;
				if(response.data.length > 0){
					$scope.prodcutCategory = response.data;
				} else {
					$scope.selectPage = false;
					$scope.selecedCategory = false;
					console.log($scope.selecedCategory);
				}
			})
		}

		$scope.chanegCategory = function(selectCategory){
			$scope.selecedCategory = selectCategory;
		}

		$scope.submitLink = function(links){
			$scope.loading = true;
			var idCategory = $scope.selecedCategory.id;
			$http.post("api/submitLinks",{links:links,id_category:idCategory,store:$scope.selectedCollection}).then(function(response){
				$scope.loading = false;
				$scope.uploadSuccess =true;
				$scope.products = response.data;
				console.log($scope.products);
				alert("Done");

			})
		}

		$scope.changeCollection = function(selectedCollection){
			$scope.openSubmitLinks = true;
			$scope.selectedCollection = selectedCollection;
		}

		$scope.getCollection = function(store_id){
			$scope.store_id = store_id;
			$http.get("api/getcollection",{params:{"store_id": store_id}}).then(function(response){
				$scope.collections = response.data;
			})
		}

		$scope.openModal = function()
		{
			$("#new-collection").modal();
		}

		$scope.creatNewCollection = function(store_id){
			$http.post("api/createcollection",{name:$scope.collectionName,"store_id": $scope.store_id}).then(function(response){
				$("#new-collection").modal('hide');
			});
			
		}
	});
</script>
@stop
