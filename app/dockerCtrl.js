app.controller('dockerCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    $scope.containers = [];
    $scope.createData = {
        image: 'normal',
    };
    $scope.list = function () {
        $scope.containers = [];
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
        displayName: 'Normal'
    },{
        value: 'mpi',
        displayName: 'MPI'
    }]
    
    $scope.start = function (container) {
        Data.post('start',{
            cid: container.cid,
            ip: container.ip
        }).then(function (results) {
            if (results.status == "success") {
                container.status = results.containerStatus;
                container.port = results.port; 
            }
            Data.toast(results);
        });
    }

    $scope.stop = function (container) {
        Data.post('stop',{
            cid: container.cid,
            ip: container.ip
        }).then(function (results) {
            if (results.status == "success") { 
                container.status = results.containerStatus;
                container.port = results.port;
            }
            Data.toast(results);
        });
    }

    $scope.create = function(createData) {
        var nameExist = false;
        var errorMsg = {
            status: 'error',
            message: 'This name already exist'
        };
        angular.forEach($scope.containers, function(value, key){
            if(createData.name == value.name){
                nameExist = true;
            }
        })
        if(nameExist) {
            Data.toast(errorMsg);
        }
        else {
            Data.post('create',{
                data: createData,
                username: $rootScope.name
            }).then(function (results) {
                if (results.status == "success") {
                    $scope.containers.push(results.data);
                }
                Data.toast(results);
            });
        }
        $scope.resetModal();
        $('#createModal').modal('hide');
    }

    $scope.remove = function (container) {
        Data.post('remove',{
            cid: container.cid,
            ip: container.ip
        }).then(function (results) {
            if (results.status == "success") {
                var index = $scope.containers.indexOf(container);
                $scope.containers.splice(index,1);
            }
            Data.toast(results);
        });
    }

    $scope.resetModal = function() {
        $scope.createData = {
            image: 'normal'
        }
    }
});
