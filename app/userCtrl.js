app.controller('userCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data, $mdDialog, $timeout) {
    $scope.users = [];
    $scope.createUser = {
    };

    $scope.getSuccess = false;

    $scope.list = function () {
        $scope.containers = [];
        Data.post('getUserList',{
        }).then(function (results) {
            angular.forEach(results.data, function(value, key){
                if(value.name != 'admin') {
                    $scope.users.push(value);
                }
            })
            $timeout(function(){$scope.getSuccess = true}, 500); 
        });
    }
    
    $scope.create = function(createUser) {
        Data.post('createUser',{
            user: createUser,
        }).then(function (results) {
            if (results.status == "success") {
                $scope.users.push(results.data);
            }
            Data.toast(results);
        });
        $scope.resetModal();
        $('#createUserModal').modal('hide');
    }

    $scope.remove = function (user, ev) {
        var confirm = $mdDialog.confirm()
          .title('Would you like to delete user ' + user.name + ' ?')
          .targetEvent(ev)
          .ok('OK')
          .cancel('Cancel');
        
        $mdDialog.show(confirm).then(function() {
            Data.post('removeUser',{
                user: user
            }).then(function (results) {
                if (results.status == "success") {
                    var index = $scope.users.indexOf(user);
                    $scope.users.splice(index,1);
                }
                Data.toast(results);
            });
        });
    }

    $scope.accept = function (user) {
        Data.post('registerUser',{
            user: user
        }).then(function (results) {
            if (results.status == "success") {
                var index = $scope.users.indexOf(user);
                $scope.users[index].registered = "true";
            }
            Data.toast(results);
        });
    }

    $scope.resetModal = function() {
        $scope.createUser = {
        }
    }
});
