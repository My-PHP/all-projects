<div class="row"></div>
	<span class="success">{{logSucc}}</span>
	<div ng-controller="viewUser">
		<div class="col-md-12">
			<div class="col-md-6">
				<form class="form" ng-pristine ng-model="post.postForm" ng-submit="postOnTimeline()" name="postForm">
					<div class="form-group">
						<label>Post Something</label>
						<textarea name="post_message" ng-model="post.post_message" required placeholder="Write somthing" class="form-control post_message" ng-pattern="msgFormat"></textarea>
						<span ng-show="!postForm.post_message.$error.pattern && postForm.post_message.$touched && postForm.post_message.$error.required" class="error">Please Enter Message</span>
						<span ng-show="!postForm.post_message.$error.required && postForm.post_message.$touched && postForm.post_message.$invalid" class="error">Please Enter Valid Message minimum 5 or maximum 250 characters</span>
					</div>
					<div class="form-group">
						<button name="submit" type="submit" class="btn btn-primary">Post</button>
					</div>
				</form>
			</div>
			<div class="col-md-6">
				<div ng-show="success" class="alert alert-block alert-success fade in" style="text-align: center;">
					<button data-dismiss="alert" class="close close-sm" type="button">
						<i class="fa fa-times"></i>
					</button>
				{{SuccessMsg}}</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<div class=" col-md-6 pull-left">
			<div class="well col-md-12 posts" ng-repeat="value in postData">
				<label><span class="post-user-name">{{value.fname}}</span></label>
				<p class="post-msg"><b><i>{{value.post_msg}}</i></b></p>
				<p class="post-date"><b><i>posted :{{ value.posted_date | timeago }}</i></b></p>
			</div>
		</div>
		<div class=" col-md-6 pull-right list_style">
			<ul class="nav nav-tabs">
				<li class="{{friends}}"><a href="#" ng-click="changeClass('')">Friends</a></li>
				<li class="{{friendAdd}}"><a href="#" ng-click="changeClass('ADD')">Add Friend</a></li>
				<li class="{{requestTab}}"><a href="#" ng-click="changeClass('Request')">Friend Request</a></li>
				<li class="{{MessageCL}}"><a href="#" ng-click="changeClass('Message')">Message<sup ng-show="numerOFmsg">{{numerOFmsg}}</sup></a></li>
			</ul>
			<br>
			<ul class="well col-md-12 friend-list" ng-if="listFriends">
				<li ng-repeat="value in friendList" ng-if="friendList.length">
					<img src="{{value.profile_pic_url}}" style="width: 50px; height: 40px;margin: 0 5px 5px 2px">
					<a href="javascript:void(0)" ng-click="vievUserDetails(value.user_id)" data-toggle="modal" data-target="#chatModal" widget ><label>{{value.fname}}</label></a>
					<button   class="btn btn-primary pull-right"><span class="glyphicon glyphicon-check"></span></button>
					
				</li>
				<li ng-if="!friendList.length"><label>No Friends</label></li>
			</ul>
			<ul class="well col-md-12 friend-list" ng-if="viewAdd">
				<li ng-repeat="value in userList" ng-if="userList.length">
					<img src="{{value.profile_pic_url}}" style="width: 50px; height: 40px;margin: 0 5px 5px 2px">
					<a href="#" ng-click="vievUserDetails(value.user_id)"><label>{{value.fname}}</label></a>
					<button  class="btn btn-primary pull-right" ng-click="addFriend(value.user_id)">Add Friend</button>
				</li>
				<li ng-if="!userList.length"><label>No Users Available</label></li>
			</ul>
			<ul class="well col-md-12 friend-list" ng-if="viewRequest">
				<li ng-repeat="x in friendRequests" ng-if="friendRequests.length">
					<img src="{{x.profile_pic_url}}" style="width: 50px; height: 40px;margin: 0 5px 5px 2px">
					<a href="#" ng-click="vievUserDetails(x.user_id)"><label>{{x.fname}}</label></a>
					<button  class="btn btn-primary pull-right" ng-click="updateRequest(x.user_id,'Accepted')">Accept</button>
					<button  class="btn btn-primary pull-right" ng-click="updateRequest(x.user_id,'Unfriend')">Cancel</button>
					
					
				</li>
				<li ng-if="!friendRequests.length"><label>No Friend Request</label></li>
			</ul>
			<ul class="well col-md-12 friend-list" ng-if="viewMsG">
				<li ng-repeat="x in userMessage" ng-if="userMessage.length" class="well">
					<p ng-click="vievUserDetails(x.user_id)">{{x.message}}</p>
					<a href="#" ng-click="vievUserDetails(x.user_id)"><label>{{x.fname}}</label></a>
					<!-- <img src="{{x.profile_pic_url}}" style="width: 50px; height: 40px;margin: 0 5px 5px 2px" class="pull-right"> -->	
				</li>
				<li ng-if="!userMessage.length"><label>No Message</label></li>
			</ul>
		</div>

		<!-- chat widget -->
	
			<div class="container wdget" ng-if="openChat">
			    <div class="row">
			        <div class="col-md-5">
			            <div class="panel panel-primary">
			                <div class="panel-heading">
			                    <span class="glyphicon glyphicon-comment"></span> {{viewUserD_.fname}}
			                    <div class="btn-group pull-right">
			                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" ng-click="closeChat()">
			                            <span class="glyphicon glyphicon-remove"></span>
			                        </button>			                        
			                    </div>
			                </div>
			                <div class="panel-body">
			                	<div ng-bind-html="all_html">
			                	<!-- <div ng-repeat="value in allMessage" ng-if="allMessage !=''"> -->
			                    	<!-- <div  style="padding:1px;" class="pull-left well col-md-7" ng-if="userId != value.sender_id">
			                    		<p>{{value.message}}</p>
			                    		<span style="float:right;font-size: 9px;">{{value.time_ago}}</span>
			                    	</div>
			                    	<div style="padding:1px;" class="pull-right well col-md-7" ng-if="userId == value.sender_id">
			                    		<p>{{value.message}}</p>
			                    		<span style="float:right;font-size: 9px;">{{value.time_ago}}</span>
			                    	</div>
			                	</div>
			                	<div  ng-if="allMessage ==false">Let's chat</div> -->
			                </div>
			                <div class="panel-footer">
		                    	<form method="post"  ng-model="cmsg.chatFrom"  ng-submit="sendMessage(viewUserD_.user_id)"  name="chatFrom" novalidate ng-prestine>
				                    <div class="form-group">
				                        <textarea class="form-control" name="chat_message" ng-model="cmsg.chat_message" placeholder="Type your message here..." required ng-pattern="msgFormat" ng-trim="true"></textarea>
				                        <span class="error" ng-show="chatFrom.chat_message.$error.required && chatFrom.chat_message.$touched && !chatFrom.chat_message.$error.pattern">Message is required.</span><span class="error" ng-show="!chatFrom.chat_message.$error.required && chatFrom.chat_message.$error.pattern && chatFrom.chat_message.$touched">Enter Valid Message.</span>
				                    </div>
				                    <div class="form-group">
				                        <span class="input-group-btn">
				                            <button class="btn btn-success btn-sm" id="btn-chat" type="submit">
				                                Send</button>
				                        </span>
				                    </div>
		                        </form>
			                </div>
			            </div>
			        </div>
			    </div>
			</div>
	
	</div>
</div>
		<!-- chat widget -->
		