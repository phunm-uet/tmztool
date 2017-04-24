
app.controller("likes", function ($scope,$rootScope,$http) {
  $scope.labels = ["", "", "", "", "", "", ""];

  $http.get("/api/marketing/getdatas",{
    params : { id : $rootScope.id ,type : "likes"}
  }).then(function(resp){
    
    var week1 = resp.data.week1[0].values;
    var week2 = resp.data.week2[0].values;
    var datas1 = [];
    var datas2 = [];
    for(i = 0; i < week1.length;i++){
      datas1.push(week1[i].value);
      datas2.push(week2[i].value);
    }
    $scope.data = [datas1,datas2];
  }).catch(function(err){
    alert("Da xay ra loi");
  });
  $scope.datasetOverride = [{ yAxisID: 'y-axis-1' }, { yAxisID: 'y-axis-2' }];
  $scope.options = {
    scales: {
      yAxes: [
        {
          id: 'y-axis-1',
          type: 'linear',
          display: true,
          position: 'left'
        },
        {
          id: 'y-axis-2',
          type: 'linear',
          display: true,
          position: 'right'
        }
      ]
    }
  };
});