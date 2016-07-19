app = angular.module('myApp', ['ngRoute', 'ngAnimate', 'toaster', 'ngMaterial']);

app.config(['$routeProvider',
  function ($routeProvider) {
        $routeProvider.
        when('/login', {
            title: 'Login',
            templateUrl: 'partials/login.html',
            controller: 'authCtrl'
        })
            .when('/logout', {
                title: 'Logout',
                templateUrl: 'partials/login.html',
                controller: 'logoutCtrl'
            })
            .when('/dashboard', {
                title: 'Dashboard',
                templateUrl: 'partials/dashboard.html',
                controller: 'authCtrl'
            })
            .when('/', {
                title: 'Login',
                templateUrl: 'partials/login.html',
                controller: 'authCtrl',
                role: '0'
            })
            .otherwise({
                redirectTo: '/login'
            });
  }])
    .run(function ($rootScope, $location, Data) {
        $rootScope.$on("$routeChangeStart", function (event, next, current) {
            $rootScope.authenticated = false;
            $rootScope.name = "";

            Data.get('session').then(function (results) {
                
                if (results.name != 'Guest') {
                    $rootScope.authenticated = true;
                    $rootScope.name = results.name;
                    if($rootScope.name == 'admin') {
                        $rootScope.isAdmin = true;
                    }
                    else {
                        $rootScope.isAdmin = false;
                    }
                } 
                else {
                    var nextUrl = next.$$route.originalPath;
                    if (nextUrl == '/signup' || nextUrl == '/login') {
                        $location.path(nextUrl);
                    } else {
                        $location.path("/login");
                    }
                }
            });
        });
    });
