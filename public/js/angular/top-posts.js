app.controller('top-posts',function($scope,$http,$rootScope,toastr){
	const HOST = "http://toanvo.com/fbtooltmz/public";
	$http.get('http://tmztool.com/api/marketing/thong-so-ads').then(function(res){
		$scope.adaccounts = res.data.adaccounts;
		$scope.niches = res.data.niches;
		$scope.selectedNiche = $scope.niches[0];
		$scope.interests = $scope.selectedNiche.interests;
		$scope.selectedAdAccount = $scope.adaccounts[0];
		$scope.gender = "1,2";
		$scope.country = "US";
		$scope.pixels = $scope.selectedAdAccount.adspixels.data;
		$scope.selectedPixel = $scope.pixels[0];
    $scope.typeAds = "POST_ENGAGEMENT";
	})
	
	$http.get("http://tmztool.com/api/marketing/get-top-posts",{
    	params : { id : $rootScope.id}
  	}).then(function(response){
      console.log(response.data);
  		$scope.posts = response.data;
  	});

  	$scope.changeNiche = function(niche)
  	{
  		$scope.interests = $scope.selectedNiche.interests;
  	};

  	$scope.changeAdAccount = function(adaccount)
  	{
      if(typeof($scope.selectedAdAccount.adspixels.data) != "undefined")
      {
        $scope.pixels = $scope.selectedAdAccount.adspixels.data;
      } else 
      {
        $scope.pixels = null;
      }
  		
  	}

  	$scope.openModal = function(id)
  	{
  		$scope.selectedPost = id;
  		$("#campaign").modal();
  	}

  	$scope.taocampaign = function()
  	{
  		console.log($scope.selectedPost);
  		var form_data = {
  			"name_campaign" : $scope.name_campaign,
	 		"country" : $scope.country,
	 		"interest" : $("#interest").val(),
	 		"post_id" : $scope.selectedPost,
	 		"minage" : $scope.minage,
	 		"maxage" : $scope.maxage,
	 		"ad_account" : $scope.selectedAdAccount,
	 		"niche" : $scope.selectedNiche.name,
	 		"pixel" : $scope.selectedPixel,
      "budget" : $scope.budget,
      "typeAds" : $scope.typeAds,
      "id" : $rootScope.id,	
  		};
  		$http.post("http://tmztool.com/api/marketing/sbAds",form_data).then(function(res){
  			$("#campaign").modal('hide');
  			toastr.success("Done","Thanh cong");
  		}).catch(function(err){
  			$("#campaign").modal('hide');
  			toastr.error("Da co loi xay ra","LOI");
  		})
  	}
});