var app = angular.module('myApp', ['ngRoute', 'ngCookies', 'ui.bootstrap','ngtimeago','ngSanitize']);
app.config(function($routeProvider, $locationProvider) {

    $routeProvider
        .when('/', {
            title: 'Login',
            templateUrl: 'view/login.php',
            controller: 'loginUser',

        }).when('/Home', {
            title: 'Home',
            templateUrl: 'view/home.php',
            controller: 'viewUser',

        }).when('/Sign-Up', {
            title: 'Sign-Up',
            templateUrl: 'view/signup.php',
            controller: 'signUpUser'

        }).when('/Profile', {
            title: 'Profile',
            templateUrl: 'view/profile.php',
            controller: 'profileCtrl'

        }).when('/Datepicker', {
            title: 'Datepicker',
            templateUrl: 'view/datepicker.php',
            controller: 'Datepicker'

        }).when('/About', {
            title: 'About Us',
            templateUrl: 'view/about.php',
            controller: 'About',
        }).otherwise({
            //title: '404'
            templateUrl:'view/404.html',
            //controller: 'notFoundCtrl'
        }); // Render 404 view ;

    $locationProvider.html5Mode(true);

});
// ...........To change the title of page..........
app.run(['$rootScope', function($rootScope) {
    $rootScope.$on('$routeChangeSuccess', function(event, current, previous) {
        $rootScope.title = current.$$route.title;
    });
}]);
// ...........To change the title of page..........

// ...........file upload directives................
app.directive('fileModel', ['$parse', function($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;

            element.bind('change', function() {
                scope.$apply(function() {
                    modelSetter(scope, element[0].files[0]);
                });
            });
        }
    };
}]);


app.factory('fileUpload', ['$http', '$window', '$q', function($http, $window, $q) {
    return {
        uploadFileToUrl: function(file, uploadUrl) {
            var deferred = $q.defer();
            var fd = new FormData();
            var Profile_pic = '';
            fd.append('file', file);
            $http.post(uploadUrl, fd, {
                    transformRequest: angular.identity,
                    headers: {
                        'Content-Type': undefined
                    }
                })
                .then(function(response) {
                    deferred.resolve(response.data);
                })
                .catch(function(response) {
                    deferred.reject(response);
                });
            return deferred.promise;
        }
    }
}]);


// ...........file upload directives end..........
app.directive('datetimepicker', [

  function() {
    var link;
    link = function(scope, element, attr, ngModel) {
        console.log(ngModel);
        element = $(element);
        element.datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            defaultDate: ngModel.$viewValue
        });
        element.on('dp.change', function(event) {
            scope.$apply(function() {
                ngModel.$setViewValue(event.date._d);
            });
        });
    };
return {
  restrict: 'A',
  scope: {
    user: '=ngModel'
  },
  link: link
};
}
]);

