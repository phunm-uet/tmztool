app.controller("changeTime", function ($scope,$rootScope,$http){
	$scope.change7days = function()
	{
		$rootScope.$broadcast('days', 7);
	}
});