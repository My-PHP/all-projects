<nav class="navbar navbar-inverse" ng-if="loggedInUser" ng-cloak ng-controller="Menu">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="Home"><img src="./uploads/logo/pic_angular.jpg" title="ANGULAR" style="width:100%;border: inset 5px;border-color: white" class="img img-circle"></a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav margiTop">
        <li  ng-class="navClass('Home')"><a href="Home">Home</a></li>
        <li  ng-class="navClass('About')"><a href="About">About</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right ">
        <li class="dropdown" style="text-align: right;">
          <a href="javascript:void(0);" class="dropdown-toggle"  data-toggle="dropdown"><img src="{{userPic}}" title="{{userName}}" style="width: 20%;border: inset 5px;border-color: white" class="img img-circle"></a>
          <ul class="dropdown-menu">
           <li ng-controller="logoutUser"><a href="Profile" ><span class="glyphicon glyphicon-user"></span>Profile</a></li>
           <li ng-controller="logoutUser"><a href="javascript:void(0);" ng-click="RemoveCookie()"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
         </ul>
       </li>
     </ul>
     
   </div>
 </div>
</nav>

