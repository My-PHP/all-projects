//controller for Home
app.controller('viewUser', function($scope, $http, $timeout, $rootScope, $cookieStore, $window, $filter, $location, $interval) {
  $scope.is_login = $cookieStore.get('is_login');
  if (angular.isUndefined($scope.is_login)) {
    window.location.href = '/start_angular';
    return false;
  }
  $scope.userId = $cookieStore.get('user_id');
  $rootScope.loggedInUser = true;
  $scope.friends = "Active_tab";
  $scope.listFriends = 1;


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
    $scope.users = response.data;
  });

    // get all posts
    $http({
      method: "post",
      url: "model/post.php",
      data: {
        'get_post': 1,
        'user_id': $scope.userId,
      },
      headers: {
        'Content-Type': 'application/json'
      }
    }).
    then(function(response) {
      angular.forEach(response.data.posts, function(value, key) {
        response.data.posts[key].posted_date = new Date(response.data.posts[key].posted_date);
      });
      $scope.postData = response.data.posts;
    });
    // Get add friends list
    $http({
      method: "post",
      url: "model/post.php",
      data: {
        'user_list': 1,
        'user_id': $scope.userId
      },
      headers: {
        'Content-Type': 'application/json'
      }
    }).
    then(function(response) {
      $scope.userList = response.data.users;
    });
    //get all friend requests
    $http({
      method: "post",
      url: "model/post.php",
      data: {
        'view_request': 1,
        'user_id': $scope.userId
      },
      headers: {
        'Content-Type': 'application/json'
      }
    }).
    then(function(response) {
        // angular.forEach(response.data.posts, function(value, key){
        //     response.data.posts[key].posted_date = new Date(response.data.posts[key].posted_date);         
        //   });
        $scope.friendRequests = response.data.requests;
        //console.log($scope.friendRequests);
      });
    // get friends list
    $http({
      method: "post",
      url: "model/post.php",
      data: {
        'friend_list': 1,
        'user_id': $scope.userId
      },
      headers: {
        'Content-Type': 'application/json'
      }
    }).
    then(function(response) {
        // angular.forEach(response.data.posts, function(value, key){
        //     response.data.posts[key].posted_date = new Date(response.data.posts[key].posted_date);         
        //   });
        $scope.friendList = response.data.friends;
      });

    /*post messeges*/
    $scope.openChat = false;
    $scope.post = {};
    $scope.msgFormat = /^[A-Za-z0-9-!@#$%&*()|'"?><._\s]{2,250}$/;
    $scope.postOnTimeline = function() {
      if ($scope.postForm.$invalid) {
        angular.forEach($scope.postForm.$error, function(field) {
          angular.forEach(field, function(errorField) {
            errorField.$setTouched();

          });
        });
        return;
      } else {

        var data = {
          'user_id': $scope.userId,
          'post': '1',
          'post_id': $scope.post.post_id,
          'message': $scope.post.post_message,

        };
        var url = "model/post.php";
        $http.post(url, data).then(function(response, status, headers, config) {
          $scope.result = false;
          $scope.result = response.data;
          if ($scope.result == true) {

            $scope.SuccessMsg = "Post submitted";
            $timeout(function() {
              $location.path('/');
            }, 2000);
          } else {
            $scopeErrorMsg = 1;
          }

        });
      }


    }
    /*post messeges*/

    /*addFriend*/
    $scope.addFriend = function(toFriend, btn) {

      $scope.success = false;
      var data = {
        'add_friend': '1',
        'user_id': $scope.userId,
        'to_friend': toFriend,
      };
      var url = "model/post.php";
      $http.post(url, data).then(function(response, status, headers, config) {
        $scope.result = false;
        $scope.result = response.data;
        if ($scope.result == true) {
          $scope.addsuccess = true;
          $scope.SuccessMsg = "Your request has been sent successfully";
          $timeout(function() {
            $location.path('/');
          }, 1000);
        } else {
          $scopeErrorMsg = 1;
        }

      });
    }
    /*addFriend end*/
    /*accept rerquest*/
    $scope.updateRequest = function(toFriend, status) {
      $scope.success = false;
      var data = {
        'accept': '1',
        'user_id': $scope.userId,
        'to_friend': toFriend,
        'status': status,
      };
      var url = "model/post.php";
      $http.post(url, data).then(function(response, status, headers, config) {
        $scope.result = false;
        $scope.result = response.data;
        if ($scope.result == true) {
          $scope.success = true;
          $scope.SuccessMsg = "Your have been Accepted Request successfully";
          $timeout(function() {
            $location.path('/');
          }, 1000);
        } else {
          $scopeErrorMsg = 1;
        }

      });
    }
    /*accept rerquest*/

    $scope.changeClass = function(tab) {
      if (tab === "Request") {
        $scope.requestTab = "Active_tab";
        $scope.friendAdd = "";
        $scope.friends = "";
        $scope.MessageCL = "";
        $scope.viewRequest = 1;
        $scope.viewAdd = 0;
        $scope.listFriends = 0;
        $scope.viewMsG = 0;
      } else if (tab === 'ADD') {
        $scope.requestTab = "";
        $scope.friendAdd = "Active_tab";
        $scope.MessageCL = "";
        $scope.friends = "";
        $scope.viewRequest = 0;
        $scope.viewAdd = 1;
        $scope.listFriends = 0;
        $scope.viewMsG = 0;
      } else if (tab === 'Message') {
        $scope.requestTab = "";
        $scope.friendAdd = "";
        $scope.MessageCL = "Active_tab";
        $scope.friends = "";
        $scope.viewRequest = 0;
        $scope.viewAdd = 0;
        $scope.viewMsG = 1;
        $scope.listFriends = 0;
      } else {
        $scope.requestTab = "";
        $scope.friends = "Active_tab";
        $scope.friendAdd = "";
        $scope.MessageCL = "";
        $scope.viewRequest = 0;
        $scope.viewAdd = 0;
        $scope.listFriends = 1;
        $scope.viewMsG = 0;
      }
    };

    /*vievUserDetails starts */
    $scope.vievUserDetails = function(toFriend) {
      var data = {
        'view_user_detail': '1',
        'user_id': $scope.userId,
        'to_friend': toFriend
      };
      var url = "model/post.php";
      $http.post(url, data).then(function(response, status, headers, config) {
        $scope.result = false;
        $scope.viewUserD_ = response.data;
        if ($scope.viewUserD_) {
          $scope.openChat = true;
          $scope.toFriend_msgId = $scope.viewUserD_.user_id;
          $scope.getMessage($scope.toFriend_msgId);
          $scope.messegeCount();
        }
      });
    }
    /*vievUserDetails ends*/

    /*save chat message*/
    $scope.cmsg = {};
    $scope.sendMessage = function(to_user_id) {
      var t = $scope.cmsg.chat_message;
      if (t.length <= 0) {
        angular.forEach($scope.chatFrom.$error, function(field) {
          angular.forEach(field, function(errorField) {
            errorField.$setTouched();

          });
        });
        console.clear();
        return;
      } else {

        var data = {
          'send_message': 1,
          'user_id': $scope.userId,
          'to_user_id': to_user_id,
          'message': $scope.cmsg.chat_message,
        }
            /* $window.alert(data);
             console.log(data)
             return false;*/
             var url = "model/post.php";
             $http.post(url, data).then(function(response, status, headers, config) {
              $scope.save = false;
              $scope.save = response.data;
              if ($scope.save == true) {
                $scope.cmsg.chat_message = "";
                $scope.chatFrom.$setUntouched();
                $scope.getMessage(to_user_id);
              }
            });
           }


         }
         /*save chat message*/

         /*close chat*/
         $scope.closeChat = function() {
          $scope.allMessage = {};
          $scope.openChat = false;
          $scope.toFriend_msgId = '';
        }
        /*close chat*/
        $scope.getMessage = function(to_user_id) {
          var data = {
            'view_message': '1',
            'user_id': $scope.userId,
            'to_user_id': to_user_id,
          };
          var url = "model/post.php";
          $http.post(url, data).then(function(response, status, headers, config) {
            angular.forEach(response.data.message, function(value, key) {
              response.data.message[key].sended_date = new Date(response.data.message[key].sended_date);
            });
            $scope.allMessage = response.data.message;

            $scope.append();
            //$scope.chatFrom.$setUntouched();

          });
        }

        $scope.append = function() {
          $scope.all_html = "";
          angular.forEach($scope.allMessage, function(value, key) {
            if ($scope.allMessage[key].sender_id == $scope.userId) {
              $scope.all_html += '<div class="pull-left well chat-msgs col-md-7" ><p>' + $scope.allMessage[key].message + '</p><span class="chat-ago" >' + $scope.allMessage[key].time_ago + '</span></div>';

            } else {
              $scope.all_html += '<div  class="pull-right well chat-msgs col-md-7" n><p>' + $scope.allMessage[key].message + '</p><span class="chat-ago">' + $scope.allMessage[key].time_ago + '</span></div>';
            }
          });


        }

        /*get message* every 15 seconds*/

        $interval(function() {
          if (angular.isDefined($scope.toFriend_msgId)) {
            $scope.getMessage($scope.toFriend_msgId);
          }
        }, 5000);


    // get numer of messege unread for user
  $scope.messegeCount=function () {
      $http({
      method: "post",
      url: "model/post.php",
      data: {
        'number_msg': 1,
        'user_id': $scope.userId
      },
      headers: {
        'Content-Type': 'application/json'
      }
    }).
    then(function(response) {
      $scope.numerOFmsg = response.data.count;
      $scope.userMessage = response.data.users;
    });
  }
  $scope.messegeCount();

  });