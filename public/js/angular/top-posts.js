app.controller('top-posts',function($scope,$http,$rootScope){
	$http.get("/api/marketing/get-top-posts",{
    	params : { id : $rootScope.id}
  	}).then(function(response){
  		$scope.posts = response.data;
  	});
});