<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Tool Import Product</title>
	<!-- Latest compiled and minified CSS & JS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script src="//code.jquery.com/jquery.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
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
</head>
<body  ng-app="tool" ng-controller="toolCtrl">
<div id="loading" ng-show="loading">
  <img id="loading-image" src="https://yudyx86.files.wordpress.com/2015/06/loading.gif" alt="Loading..." />
</div>
	<div class="container">
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
		</div>
		<div class="col-xs-6 col-xs-offset-2">
			<div class="panel panel-primary">
				<div class="panel-heading">Page info</div>
				<div class="panel-body">
					<div class="content row">
						<div class="col-xs-4">
							<img ng-src="@{{ selectedPage.picture.data.url}}" alt="" id="imgPage">
						</div>
						<div class="col-xs-8">
							<p id="namePage">@{{selectedPage.name}}</p>
						</div>						
					</div>
					<div class="row">
						<p>Upload Succes: </p>
						<div class="products" ng-if="uploadSuccess">
							<p ng-repeat="product in products">
									<a href="https://www.facebook.com/@{{product.id}}" target="_blank">@{{product.name}}</a>
							</p>
						</div>						
					</div>

				</div>
			</div>
		</div>
	</div>
	<div class="container" ng-if="selecedCategory">
		<form ng-submit="submitLink(links)">
			<div class="form-group">
				<label for="">Enter Link Shopify</label>
				<textarea class="form-control" rows="8" id="comment" ng-model="links" ng-list="&#10;" ng-trim="false"></textarea>
			</div>
			<button class="btn btn-block btn-success">Submit</button>
		</form>
	</div>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.3/angular.min.js"></script>
<script>
	var app = angular.module("tool",[]);
	app.controller("toolCtrl",function($scope,$http){
		$scope.selectedPage = false;
		$scope.selecedCategory = false;
		$scope.loading = false;
		$scope.uploadSuccess = false;
		$http.get("api/pages").then(function(response){
			 $scope.pages = response.data;
			 $scope.loading = false;
		})
		$scope.changePage = function(selectePage){
			// console.log(selectePage);
			$scope.selectedPage = selectePage;
			$scope.selectPage = true;
			$scope.loading = true;
			$http.get("api/product_category",{params:{"id": selectePage.id}}).then(function(response){
				// console.log(response.data.length);
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
			$http.post("api/submitLinks",{links:links,id_category:idCategory}).then(function(response){
				$scope.loading = false;
				$scope.uploadSuccess =true;
				$scope.products = response.data;
				console.log($scope.products);
				alert("Done");

			})
		}
	});
</script>