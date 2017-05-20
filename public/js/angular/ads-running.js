app.controller("ads-running", function ($scope,$rootScope,$http,$q){
	var HOST = "http://tmztool.com/";
	$http.get(HOST+'api/marketing/ads-running',{params:{id: $rootScope.id}}).then(function(response){
		var campaigns = response.data;
		$scope.campaigns = campaigns;
		var chain = $q.when();
		angular.forEach($scope.campaigns ,function(campaign){
			chain = chain.then(function(){
				return $http.get('http://tmztool.com/api/marketing/get-campaign-info',
					{params:{campaign_id : campaign.campaign_id}})
					.then(function(resp){
						console.log(resp.data);
						campaign.image = resp.data.post.picture;
						campaign.reaction = resp.data.post.reactions.summary.total_count;			
						campaign.comment = resp.data.post.comments.summary.total_count;			
						campaign.share = resp.data.post.shares.count;			
						campaign.cpc = resp.data.campaign.cpc;
						campaign.cpm = resp.data.campaign.cpm;
						campaign.spent = resp.data.campaign.spend;
					});
			});
		});
		chain.then(function(){
   
		});		
	})

	$scope.pause = function(campaign_id)
	{
		$http.post(HOST+'api/marketing/pause-ads',{campaign_id : campaign_id }).then(function(response){
			if(response.data.success == 1)
			{
				alert("Done");
			}
		});
	}
});