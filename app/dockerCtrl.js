app.controller('dockerCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    $scope.containers = [];
    $scope.list = function () {
        Data.post('getList',{
            username: $rootScope.name
        }).then(function (results) {
            angular.forEach(results.data, function(value, key){
                $scope.containers.push(value);
            })
        });
    }
    if($rootScope.name != null) {
        $scope.list();
    }

    $scope.check = function () {
        Data.post('checkStatus',{

        }).then(function (results) {

        });
    };

});
