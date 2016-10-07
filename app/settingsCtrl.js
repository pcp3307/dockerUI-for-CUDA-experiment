app.controller('settingsCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data, $mdDialog, $timeout) {
  
    $scope.resources = [];

    $scope.getSuccess = false;

    $scope.list = function () {
        Data.post('getResource',{
        }).then(function (results) {
            angular.forEach(results.data, function(value, key){
                $scope.resources.push(value);
            })
            $timeout(function(){$scope.getSuccess = true}, 500); 
        });
    }
  

  $scope.add = function(data) {
        var ipExist = false;
        var errorMsg = {
            status: 'error',
            message: 'This ip already exist'
        };
        angular.forEach($scope.resources, function(value, key){
            if(data.ip == value.ip){
                ipExist = true;
            }
        })
        if(ipExist) {
            Data.toast(errorMsg);
            $('#createModal').modal('hide');
        }
        else {
            Data.post('addResource',{
                ip: data.ip,
            }).then(function (results) {
                Data.toast(results);
                if (results.status == "success") {
                    $scope.resources.push(results.data);
                }
                $('#createModal').modal('hide');
            });
        }
    }

    $scope.remove = function (resource, ev) {
        var confirm = $mdDialog.confirm()
          .title('Would you like to delete resource ' + resource.ip + ' ?')
          .targetEvent(ev)
          .ok('OK')
          .cancel('Cancel');
        
        $mdDialog.show(confirm).then(function() {
            Data.post('removeResource',{
                ip: resource.ip
            }).then(function (results) {
                if (results.status == "success") {
                    var index = $scope.resources.indexOf(resource);
                    $scope.resources.splice(index,1);
                }
                Data.toast(results);
            });    
        });
        
    }


});
