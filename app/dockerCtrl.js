app.controller('dockerCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    $scope.containers = [];
    $scope.createData = {};
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

    $scope.imagelist = [{
        value: 'normal',
        displayName: 'Nomal'
    },{
        value: 'mpi',
        displayName: 'MPI'
    }]

    $scope.start = function (container) {
        Data.post('start',{
            cid: container.cid
        }).then(function (results) {
            if (results.status == "success") {
                container.status = 'true';
            }
            Data.toast(results);
        });
    }

    $scope.stop = function (container) {
        Data.post('stop',{
            cid: container.cid
        }).then(function (results) {
            if (results.status == "success") {
                container.status = 'false';
            }
            Data.toast(results);
        });
    }

    $scope.remove = function (cid) {
        Data.post('remove',{
            cid: cid
        }).then(function (results) {
            Data.toast(results);
        });
    }

});
