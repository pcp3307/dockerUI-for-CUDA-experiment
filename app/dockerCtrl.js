app.controller('dockerCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    $scope.list = function () {
        Data.post('getList',{
            username: $rootScope.name
        }).then(function (results) {
            Data.toast(results);
        });
    }

});
