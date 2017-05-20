var app = angular.module("ads",['toastr','ngFileUpload']);
app.constant('HOST', window.location.origin+"/fbtooltmz/public");
app.run(function($http,$rootScope,HOST){
	$rootScope.loading = true;
	$rootScope.hoanthanh = false;
	$http.get(HOST+"/api/marketing/index").then(function(response){
		$rootScope.loading = false;
		var data = response.data;
		$rootScope.niches = data.niches;
		$rootScope.pages = data.pages;
		$rootScope.adaccounts = data.adaccounts;
		$rootScope.gender = "1,2";
		$rootScope.optionImage = "1";
		// set first option
		$rootScope.selectedNiche = $rootScope.niches[0];
		$rootScope.country = "US";
		$rootScope.selectedPage = $rootScope.pages[0];
		$rootScope.selectedAdAccount = $rootScope.adaccounts[0];
		$rootScope.pixels = $rootScope.selectedAdAccount.adspixels.data;
		$rootScope.selectedPixel = $rootScope.pixels[0];
		$rootScope.interests = $rootScope.selectedNiche.interests;
	}).catch(function(error){
		console.log(error);
	});
});
app.controller("adsController",function($http,$scope,HOST,toastr,Upload){

	$scope.changeNiche = function(selectedNich) {
		$scope.interests = $scope.selectedNiche.interests;
	}

	$scope.uploadFiles = function(file,error)
	{
		$scope.loading = true;
		$scope.f = file;
        $scope.errFile = error && error[0];
        if(file){
        	file.upload = Upload.upload({
        		url : 'http://tmztool.com/api/marketing/upload',
        		data : {file: file}
        	});
        	file.upload.then(function(resp){
        		$scope.image_link = resp.data.link;
        		toastr.success("Done","Upload Hinh thanh cong");
        		$scope.loading = false;
        	}).catch(function(e){
        		alert("Có lỗi trong quá trình upload");
        		$scope.loading = false;
        	});
        }
	}

	// Handle event paste Product Link
	$scope.paste = function (event) {
		console.log($scope.selectedAdAccount);
		if($scope.optionImage == 2) return;
	  	$scope.loading = true;
	    var item = event.clipboardData.items[0];
	    item.getAsString(function (data) {
	      var link = data;
	      $http.post(HOST+"/api/marketing/image_link",{link : link}).then(function(response){
	      	$scope.loading = false;
	      	$scope.image_link = response.data.image_link;
	      }).catch(function(error){
	      	alert(error);
	      });
	      $scope.$apply();
	    });
	  };
	  //Handle event Change AdAccounts
	  $scope.changeAdAccount = function()
	  {
	  	if($scope.selectedAdAccount.adspixels){
	  		$scope.pixels = $scope.selectedAdAccount.adspixels.data;
	  		if($scope.pixels) $scope.selectedPixel = $scope.pixels[0];
	  	} else {
	  		$scope.pixels = null;
	  	}
	  	
	  	
	  }

	  $scope.uploadImage = function(image){
	  	console.log(image);
	  }
	  // Create Campaign
	 $scope.createCampaign = function(){
	 	$scope.loading = true;
	 	var form_data = {
	 		"country" : $("#country").val(),
	 		"interest" : $scope.selectedInterest,
	 		"page" : $scope.selectedPage,
	 		"minage" : $scope.minage,
	 		"maxage" : $scope.maxage,
	 		"image_link" : $scope.image_link,
	 		"product_link" : $scope.product_link,
	 		"description" : $scope.description,
	 		"ad_account" : $scope.selectedAdAccount,
	 		"niche" : $scope.selectedNiche.name,
	 		"pixel" : $scope.selectedPixel,
	 		"typeAds" : $scope.typeAds,
	 		"typeProduct" : $scope.selectedType
	 	};
	 	$http.post(HOST+"/api/marketing/submitAds",form_data).then(function(response){
	 		$scope.loading = false;
	 		$scope.hoanthanh = true;
	 		var data = response.data;
	 		$scope.ads = data.ads;
	 		$scope.adsets = data.adsets;
	 		$scope.campaign = data.campaign;
	 		$scope.post = data.post;
	 		$scope.linkPost = "https://facebook.com/"+$scope.post;
	 		toastr.success("Done","Thanh cong");
	 	});
	 }
		
});