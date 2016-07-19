app.controller('authCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data) {
    //initially set those objects to null to avoid undefined error
    $scope.login = {};

    $scope.isLogin = false;

    $scope.doLogin = function (user) {
        if(!$scope.isLogin){
            $scope.isLogin = true;
            Data.post('login', {
                user: user
            }).then(function (results) {
                if (results.status == "success") {
                    Data.toast(results);
                    $location.path('dashboard');
                }
                else {
                    msg = {'status':'error', 'message':'Login failed. Incorrect credentials'};
                    Data.toast(msg);
                }
                $scope.isLogin = false;
            });
        }
    };
    $scope.logout = function () {
        Data.get('logout').then(function (results) {
            Data.toast(results);
            $location.path('login');
        });
    }
});
