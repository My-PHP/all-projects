<div class="row profile">
	<div class="col-md-6 col-sm-12">
		<form name="profile" ng-model='profile' ng-pristine novalidate ng-controller="profileCtrl" ng-submit="updateUser()">
			<div class="col-md-4 form-group">
				<label>Profile Picture <span class="error">*</span></label>
			</div>
			<div class="col-md-8 form-group">
				<img src="{{user.profile_pic_url}}" width="100" height="80"><br>
				<input class="form-control" type="file" name="userFIle" file-model="myFile" accept="image/*">
				<span ng-show="!user.profile_pic_url" class="error">Please Upload Profile pic</span>
			</div>
			<div class="col-md-4 form-group">
				<label>User Name <span class="error">*</span></label>
			</div>
			<div class="col-md-8 form-group">			
				<input class="form-control" type="text" name="fname" ng-model="user.fname" ng-pattern="textLimit" required>
				<span ng-show=" profile.fname.$touched && profile.fname.$error.required && !profile.fname.$error.pattern" class="error">Please Enter Name</span>

				<span ng-show="!profile.fname.$error.required && profile.fname.$touched && profile.fname.$invalid" class="error">Please Enter Valid Name</span>
				
			</div>
			<div class="col-md-4 form-group">
				<label>User Email <span class="error">*</span></label>
			</div>
			<div class="col-md-8 form-group">			
				<input class="form-control" type="email" name="email" ng-model="user.email" ng-pattern="emailFormat" ng-change="checkEmail()" required>
				<span ng-show="profile.email.$touched && profile.email.$error.required && !profile.email.$error.pattern" class="error">Please Enter Email</span>
					<span ng-show="!email_check &&profile.email.$invalid && profile.email.$dirty && !profile.email.$error.required && !email_check" class="error">Please Enter Valid Email</span>

					<span ng-show="email_check && !profile.email.$error.required && !profile.email.$error.pattern && profile.email.$touched" class="error">Email Already Exists</span>
			</div>
			<div class="col-md-4 form-group">
				<label>User DOB <span class="error">*</span></label>
			</div>
			<div class="col-md-8 form-group">			
				<input type="text" class="form-control" uib-datepicker-popup="{{dateFormat}}" ng-model="user.dob" name="dob" is-open="availableDatePopup.opened" datepicker-options="availableDateOptions" ng-required="true" close-text="Close" ng-click="OpenAvailableDate()"
                placeholder="Date Of Birth" readonly="readonly" show-button-bar="false"/>
					<span class="error" ng-show="profile.dob.$error.required && profile.dob.$touched">Date of Birth is required.</span>	
			</div>
			<div class="col-md-4 form-group"></div>			
			<div class="col-md-4 form-group">			
				<input class="btn btn-primary" type="submit" name="submit" ng-model="submit" value="Save">
				<input class="btn btn-primary" type="reset" name="reset" ng-model="cancel" value="Cancel" ng-click="Cancel()">
			</div>
		</form>
	</div>	
</div>