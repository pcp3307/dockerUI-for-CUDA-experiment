app.controller('dockerCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    $scope.list = function () {
        Data.post('getList',{
            username: $rootScope.name
        }).then(function (results) {
            //if(results.status == "success") {
                
            //}
            Data.toast(results);
        });
    }

});
