app.controller('dockerCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data, $mdDialog, $timeout) {
    $scope.containers = [];
    $scope.createData = {
        image: 'normal',
    };

    $scope.getSuccess = false;

    $scope.list = function () {
        Data.post('getList',{
        }).then(function (results) {
            angular.forEach(results.data, function(value, key){
                $scope.containers.push(value);
            })
            $timeout(function(){$scope.getSuccess = true}, 500); 
        });
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
            Data.toast(results);
            if (results.status == "success") {
                container.status = results.containerStatus;
                container.port = results.port; 
            }
            else {
                $location.path('login');
            }
        });
    }

    $scope.stop = function (container) {
        Data.post('stop',{
            cid: container.cid,
            ip: container.ip
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") { 
                container.status = results.containerStatus;
                container.port = results.port;
            }
            else {
                $location.path('login');
            }
        });
    }

    $scope.loading = false;

    $scope.create = function(createData) {
        $scope.loading = true;
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
            $scope.resetModal();
            $('#createModal').modal('hide');
        }
        else {
            Data.post('create',{
                data: createData,
                username: $rootScope.name
            }).then(function (results) {
                $scope.loading = false;
                $scope.resetModal();
                $('#createModal').modal('hide');
                Data.toast(results);
                if (results.status == "success") {
                    $scope.containers.push(results.data);
                }
                else {
                    $timeout(function(){$location.path('login');}, 250); 
                }
            });
        }
    }

    $scope.remove = function (container, ev) {
        var confirm = $mdDialog.confirm()
          .title('Would you like to delete container ' + container.name + ' ?')
          .targetEvent(ev)
          .ok('OK')
          .cancel('Cancel');
        $scope.tempEvent = ev;
        $mdDialog.show(confirm).then(function() {
            $scope.tempEvent.target.disabled = true;
            Data.post('remove',{
                cid: container.cid,
                ip: container.ip
            }).then(function (results) {
                Data.toast(results);
                if (results.status == "success") {
                    var index = $scope.containers.indexOf(container);
                    $scope.containers.splice(index,1);
                }
                else {
                    $location.path('login');
                }

            });    
        });
        
    }

    $scope.resetModal = function() {
        $scope.createData = {
            image: 'normal'
        }
    }
});
