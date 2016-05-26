app.controller('settingCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    $scope.password = {};

    $scope.modifyPassword = function(newPassword) {
        Data.post('modifyPassword',{
            uid: $rootScope.uid,
            password: newPassword,
        }).then(function (results) {
            Data.toast(results);
            $scope.password = {};
        });
    }

    $scope.modifyEmail = function(newEmail) {
        Data.post('modifyEmail',{
            uid: $rootScope.uid,
            email: newEmail,
        }).then(function (results) {
            Data.toast(results);
        });
    }

});
