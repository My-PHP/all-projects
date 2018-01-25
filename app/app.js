//controller for Sign up
app.controller('signUpUser', function($scope, $http, $timeout, $rootScope, $window, $cookieStore, fileUpload, $filter) {

    $scope.is_login = $cookieStore.get('is_login');
    $scope.signObj = {};
    $scope.imageErr = false;
    if ($scope.is_login == true) {
        window.location.href = 'Home';
        return false;
    }
    $rootScope.loggedInUser = null;
    $rootScope.is_logged = null;
    $scope.emailFormat = /^[a-z]+[a-z0-9._]+@[a-z]+\.[a-z.]{2,5}$/;
    $scope.textLimit = /^[A-Za-z0-9-_\s]{3,25}$/;
    var today = new Date();
    $scope.AvailableDate = new Date();
    $scope.dateFormat = 'yyyy-MM-dd';
    $scope.availableDateOptions = {
        formatYear: 'yy',
        startingDay: 1,
        minDate: new Date(1951, 1, 01),
        //maxDate: new Date(2012, 1, 01),
        autoclose: true,
        //weekStart: 0
        showWeeks: false,
        //defaultDate:$scope.user.dob
    };
    $scope.availableDatePopup = {
        opened: false
    };
    $scope.OpenAvailableDate = function() {
        $scope.availableDatePopup.opened = !$scope.availableDatePopup.opened;
    };
    $scope.email_check = false;
    $scope.checkEmail = function() {
        var data = {
            'email_check': '1',
            'email': $scope.signObj.email
        };
        var url = "model/db_data.php";
        $http.post(url, data).then(function(response, status, headers, config) {
            $scope.email_check = response.data;
            if ($scope.email_check == true) {
                $scope.signUp_form.email.$setValidity("email", false);
                return;
            } else {
                $scope.signUp_form.email.$setValidity("email", true);
                return;
            }
            //$window.alert($scope.email_check);
        });
    }
    $scope.resetForm = function() {
        $scope.signObj = {};
        if ($scope.signUp_form.$invalid) {
            angular.forEach($scope.signUp_form.$error, function(field) {
                angular.forEach(field, function(errorField) {
                    errorField.$setPristine();
                });
            });

            return;
        }
    }

    $scope.SignUp = function() {
        if ($scope.signUp_form.$invalid) {
            angular.forEach($scope.signUp_form.$error, function(field) {
                angular.forEach(field, function(errorField) {
                    errorField.$setTouched();

                });
            });
            $scope.checkEmail();
            if (angular.isUndefined($scope.myFile)) {
                $scope.imageErr = true;
            } else {
                $scope.imageErr = false;
            }
            return;
        } else if (angular.isUndefined($scope.myFile)) {
            $scope.imageErr = true;
            return;
        } else {
            $scope.imageErr = false;
            var file = $scope.myFile;
            var uploadUrl = 'model/file_upload.php';
            $scope.pic = '';
            fileUpload.uploadFileToUrl(file, uploadUrl).then(function(res) {
                $scope.pic = res.fileName;
                $scope.dob = $filter('date')($scope.signObj.dateOfBirth, "yyyy-MM-dd");
                var data = {
                    'profile_pic': $scope.pic,
                    'signup': '1',
                    'fname': $scope.signObj.fName,
                    'email': $scope.signObj.email,
                    'dob': $scope.dob,
                    'password': $scope.signObj.password
                };
                var url = "model/register.php";
                $http.post(url, data).then(function(response, status, headers, config) {
                    $scope.result = false;
                    $scope.result = response.data;
                    console.clear();
                    console.log($scope.result);
                    if ($scope.result) {
                        $rootScope.logSucc = 'You are registered  Successfully';

                         window.location.href='/start_angular' 
                    } else {
                        $scope.signUpErr = 'You are not registered.Please try again';
                    }
                });

            }).catch(function(response) {
                console.log(response.status);
            });
        }

    }
});

app.controller('About', function($scope, $http, $timeout, $rootScope, $cookies, $cookieStore, $window) {

    $scope.is_login = $cookieStore.get('is_login');
    if ($scope.is_login == false || angular.isUndefined($scope.is_login)) {
        window.location.href = '/start_angular';
        return false;
    } else {
        $rootScope.loggedInUser = true;
        $rootScope.is_logged = true;

    }
    // $http({
    //         method: "post",
    //         url:"model/db_data.php",
    //         data:{'Home':1},  
    //         headers: { 'Content-Type': 'application/json' }
    //       }).
    //         then(function (response) { 
    //        $scope.users = response.data; 
    //      });
});
//controller for Home
app.controller('Menu', function($scope, $http, $timeout,$window, $rootScope, $location, $cookies, $cookieStore) {
    // $scope.getClass = function (path) {
    // return ($location.path().substr(0, path.length) === path) ? 'active' : '';
    //}
    
    $scope.userID = $cookieStore.get('user_id');
    var  data = {'user_id':$scope.userID,'menuUser':1}; 
    $http.post('model/db_data.php' , data).then(function (res) {
    
      $scope.userPic =res.data.profile_pic_url;
      $scope.userName = res.data.fname;
    });
    $scope.navClass = function(page) {
        var currentRoute = $location.path().substring(1) || 'home';
        return page === currentRoute ? 'active' : '';
    };

});
//controller for Home
app.controller('logoutUser', function($scope, $window, $cookies, $cookieStore) {
    $scope.RemoveCookie = function() {
        // $window.alert($cookieStore.get('is_login'));
        $cookieStore.remove('is_login');
        $cookieStore.remove('user_id');
        $cookieStore.remove('user_name');
        $cookieStore.remove('user_pic');
        window.location.href = '/start_angular';
    };

});

//controller for login
app.controller('loginUser', function($scope, $http, $rootScope, $window, $cookies, $cookieStore) {
    $scope.is_login = $cookieStore.get('is_login');
    if (angular.isDefined($scope.is_login)) {
        window.location.href = 'Home';
        return false;
    }
    $scope.email = $cookieStore.get('cookieEmail');
    $scope.password = $cookieStore.get('cookiePassword');
    $rootScope.loggedInUser = null;
    $scope.model = {};
    $scope.result = true;
    $scope.emailFormat = /^[a-z]+[a-z0-9._]+@[a-z]+\.[a-z.]{2,5}$/;
    $scope.login_now = function() {

      if ($scope.login_form.$invalid) {
            angular.forEach($scope.login_form.$error, function(field) {
                angular.forEach(field, function(errorField) {
                    errorField.$setTouched();

                });
            });
            return;
        }

        var data = {
            'login': '1',
            'email': $scope.email,
            'password': $scope.password
        };
        var url = "model/db_data.php";
        $http.post(url, data).then(function(response, status, headers, config) {
            $scope.result = false;
            $scope.result = response.data.is_login;
            $scope.user_id = response.data.user_id;
            $scope.user_name = response.data.user_name;
            $scope.user_pic = response.data.user_pic;
            if ($scope.result == true) {
                if (angular.isDefined($scope.remember_me)) {
                    $cookieStore.put("cookieEmail", $scope.email);
                    $cookieStore.put("cookiePassword", $scope.password);
                    $scope.email = $cookieStore.get('cookieEmail');
                    $scope.password = $cookieStore.get('cookiePassword');
                }
                $cookieStore.put("is_login", $scope.result);
                $cookieStore.put("user_id", $scope.user_id);
                $cookieStore.put("user_name", $scope.user_name);
                $cookieStore.put("user_pic", $scope.user_pic);
                // $scope.RemoveCookie = function () {
                //     $cookieStore.remove('Name');
                // };
                window.location.href = 'Home'
            } else {
                $scope.logErr = 'Email or Password are Incorrect';
            }
        });

    };

});

//controller for Forgot Password
app.controller('FogotPassword', function($scope, $http, $timeout, $rootScope, $cookieStore, $window) {
    $scope.success=false;
    $scope.is_login = $cookieStore.get('is_login');
    if (angular.isDefined($scope.is_login)) {
      window.location.href = 'Home';
        return false;
    }
    $scope.formatEmail = /^[a-z]+[a-z0-9._]+@[a-z]+\.[a-z.]{2,5}$/;
    $scope.resetForget = function() {
      $scope.forgot_email = "";
      $scope.forgotForm = "";
        if ($scope.forgotForm.$invalid) {
            angular.forEach($scope.forgotForm.$error, function(field) {
                angular.forEach(field, function(errorField) {
                    errorField.$setPristine();
                });
            });
            $scope.success=false;
            $window.alert($scope.success);
            return;
        }
    }


      $scope.fogotPassword = function() {
          var data = {
            'forgot': '1',
            'email': $scope.forgot_email,
        };
        var url = "model/db_data.php";
        $http.post(url, data).then(function(response, status, headers, config) {
          console.clear();
          console.log(response.data);
           if(response.data.msg){
            $scope.successMsg=response.data.msg;
            $scope.success=true;
            $window.alert($scope.success);
           }else{
              $scope.successMsg="";
              $scope.success=false;
           }
        });


    };

});

app.controller('profileCtrl', function($scope, $http, $timeout, $rootScope, $cookieStore, $window, $filter,fileUpload) 
{
  $scope.user ={};
  $scope.emailFormat = /^[a-z]+[a-z0-9._]+@[a-z]+\.[a-z.]{2,5}$/;
  $scope.textLimit = /^[A-Za-z0-9-_\s]{3,25}$/;
$scope.is_login = $cookieStore.get('is_login');
    if (angular.isUndefined($scope.is_login)) {
        window.location.href = '/start_angular';
        return false;
    }
    $scope.userId = $cookieStore.get('user_id');
    $rootScope.loggedInUser = true;
    $http({
        method: "post",
        url: "model/db_data.php",
        data: {
            'Home': 1,
            'user_id': $scope.userId
        },
        headers: {
            'Content-Type': 'application/json'
        }
    }).
    then(function(response) {
        $scope.user = response.data;        
       $scope.user.dob = new Date($scope.user.dob);
    });

    var today = new Date();

    $scope.AvailableDate = new Date();
    $scope.dateFormat = 'dd-MM-yyyy';
    $scope.availableDateOptions = {
        formatYear: 'yy',
        startingDay: 1,
        minDate: new Date(1951, 1, 01),
        //maxDate: new Date(2012, 1, 01),
        autoclose: true,
        //weekStart: 0
        showWeeks: false
    };
    $scope.availableDatePopup = {
        opened: false
    };
    $scope.OpenAvailableDate = function() {
        $scope.availableDatePopup.opened = !$scope.availableDatePopup.opened;
    };
    $scope.email_check = false;  
    $scope.checkEmail = function() {
        var data = {
            'email_check': '1',
            'email': $scope.user.email,
            'user_id':$scope.userId
        };
        var url = "model/db_data.php";
        $http.post(url, data).then(function(response, status, headers, config) {
            $scope.email_check = response.data;
            if ($scope.email_check == true) {
                $scope.profile.email.$setValidity("email", false);
                return;
            } else {
                $scope.profile.email.$setValidity("email", true);
                return;
            }
        });
    }

    $scope.Cancel=function () {
        window.location.href = 'Home';
    }

$scope.updateUser=function(){
        if ($scope.profile.$invalid) {          
            angular.forEach($scope.profile.$error, function(field) {
                angular.forEach(field, function(errorField) {
                    errorField.$setTouched();

                });
            });
            if (angular.isUndefined($scope.user.profile_pic_url)) {
                $scope.imageErr = true;
            } else {
                $scope.imageErr = false;
            }
            $scope.checkEmail();
            return;
        } else if (angular.isUndefined($scope.user.profile_pic_url)) {
            $scope.imageErr = true;
            return;
        }
        else{
            var file = $scope.myFile;
            var uploadUrl = 'model/file_upload.php';
             fileUpload.uploadFileToUrl(file, uploadUrl).then(function(res) {
                $scope.pic = res.fileName;
                $scope.user.Dob = $filter('date')($scope.user.dob, "yyyy-MM-dd");
                var data = {
                    'profile_pic': $scope.pic,
                    'profile': '1',
                    'fname': $scope.user.fname,
                    'email': $scope.user.email,
                    'dob': $scope.user.Dob,
                    'user_id': $scope.userId,
                };
                var url = "model/register.php";
                $http.post(url, data).then(function(response, status, headers, config) {
                    $scope.result = false;
                    $scope.result = response.data;
                    if($scope.result===true){
                      window.location.href ='Profile'
                    }
                  
                });

            }).catch(function(response) {
                console.log(response);
            });
          };

     }

});
 /*//page not found
     app.controller('notFoundCtrl', ['$scope', function($scope){
         $rootScope.loggedInUser = true;
     }]);*/