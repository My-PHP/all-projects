<div class="container">
	<div class="row login_box">
		<div class="col-sm-12">
			<form class="form" method="post" action="javascript:void(0);" id="signUp_form" name="signUp_form" ng-controller="signUpUser" ng-model="signObj.signUp_form"  role="form" ng-submit="SignUp()" novalidate ng-pristine>
				<div class=" form-group">
					<h4 id='login_box_title'>Sign Up</h4>					
					<label for="new_acc" class="pull-right new_acc"><a class="new_acc" id="new_acc" href="/start_angular" >Already have Account</a></label>
				</div>
				<div class=" form-group">
					<span ng-show="result == false" class="error">{{signUpErr}}</span>	
				</div>
				<div class=" form-group">
					<label for="fName">User Name</label>
					<input type="text" name="fName" id="fName" ng-model="signObj.fName" class="form-control" required ng-pattern="textLimit">
					<span ng-show="!signUp_form.fName.$error.pattern && signUp_form.fName.$touched && signUp_form.fName.$error.required" class="error">Please Enter Name</span>

					<span ng-show="!signUp_form.fName.$error.required && signUp_form.fName.$touched && signUp_form.fName.$invalid" class="error">Please Enter Valid Name</span>

				</div>					
				<div class=" form-group">
					<label for="email">User Email</label>
					<input type="email" name="email" id="email" ng-model="signObj.email" class="form-control"  ng-pattern="emailFormat" ng-change="checkEmail()" required>
					<span ng-show="signUp_form.email.$touched && signUp_form.email.$error.required && !signUp_form.email.$error.pattern" class="error">Please Enter Email</span>
					<span ng-show="signUp_form.email.$invalid && signUp_form.email.$dirty && !signUp_form.email.$error.required && !email_check" class="error">Please Enter Valid Email</span>

					<span ng-show="email_check && !signUp_form.email.$error.required && !signUp_form.email.$error.pattern && signUp_form.email.$touched" class="error">Email Already Exists</span>
				</div>
				<div class=" form-group">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" ng-model="signObj.password" class="form-control" required minlength="6" maxlength="15" ng-pattern="/^(?=.*[$@$!%*?&])[A-Za-z\d$@_$!%*?&]{6,15}/">
					<span class="error" ng-show="signUp_form.password.$error.required && signUp_form.password.$touched && !signUp_form.password.$error.pattern ">Password is required.</span>
					<span ng-show="signUp_form.password.$invalid && signUp_form.password.$dirty && !signUp_form.password.$error.required" class="error">Please Enter Atleast 6 character with spacial character</span>
				</div>
				<div class="form-group datePicker_margin">
					<label for="password">Date of Birth</label>
					<input type="text" class="form-control" uib-datepicker-popup="{{dateFormat | date}}" ng-model="signObj.dateOfBirth" name="dateOfBirth" is-open="availableDatePopup.opened" datepicker-options="availableDateOptions" ng-required="true" close-text="Close" ng-click="OpenAvailableDate()"
                placeholder="Date Of Birth" readonly="readonly" />
					<span class="error" ng-show="signUp_form.dateOfBirth.$error.required && signUp_form.dateOfBirth.$touched">Date of Birth is required.</span>					
				</div>
				<div class="form-group datePicker_margin">
					<label for="password">Profile Picture</label>
					 <input type = "file" file-model="myFile" class="form-control" valid-file required  accept="image/*" />
					<span class="error" ng-show="imageErr">Please upload profile picture.</span>			
				</div>
				<div class="form-group">				
					<button  type="submit" name="submit" id="submit"  class="btn btn-success">Register</button>	
					<button  type="reset" name="reset"  ng-click="resetForm()" class="btn btn-warning" >Cancel</button>

				</div>
				
			</form>			
		</div>
	</div>
</div>