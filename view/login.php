<div class="container">
	<div class="row login_box">
		<div class="col-sm-12">
			<form class="form" method="post" action="javascript:void(0);" id="login_form" name="login_form" ng-controller="loginUser" ng-model="login_form" ng-submit="login_now()" role="form" novalidate>
				<div class=" form-group">
					<h4 id='login_box_title'>Sign In</h4>					
				</div>
				<div class=" form-group">
					<span ng-show="result == false && !login_form.email.$error.required && !login_form.email.$error.pattern" class="error">{{logErr}}</span>	
						
							
				</div>
				<div class=" form-group">
					<label for="email">User Name</label>
					<input type="email" name="email" id="email" ng-model="email" class="form-control" required ng-pattern="emailFormat">
					<span class="error" ng-show="login_form.email.$error.required && login_form.email.$touched && !login_form.email.$error.pattern">Email is required.</span>
					<span ng-show="login_form.email.$invalid && login_form.email.$dirty && login_form.email.$touched && !login_form.email.$error.required" class="error">Please Enter Valid Email</span>
				</div>
				<div class=" form-group">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" ng-model="password" class="form-control" required minlength="6" maxlength="15" ng-pattern="/[a-z0-9]/">
					<span class="error" ng-show="login_form.password.$error.required && login_form.password.$touched && !login_form.password.$error.pattern">Password is required.</span>
					<span ng-show="login_form.password.$invalid && login_form.password.$dirty && login_form.password.$touched && !login_form.password.$error.required" class="error">Please Enter Valid Password</span>
				</div>
				<div class=" form-group">
				<button  type="submit" name="submit"  class="btn form-control" ><i class="fa fa-check"></i></button>	
				</div>
				<div class=" form-group">
				<label for="remeber_me" class="pull-left"><input type="checkbox" name="remeber_me" id="remeber_me" ng-model="remember_me" class="remeber_me" value="1"> Remember Me</label>
				<label for="new_acc" class="pull-left new_acc"><a class="new_acc" id="new_acc" href="Sign-Up" >Create New Account</a></label>	
				</div>
				<label for="forgot_p" class="pull-right"><a class="forgot_p" id="forgot_p" href="#" data-toggle="modal" data-target="#forgot_Model">Forgot password ?</a></label>	
				</div>
				<div class=" form-group"></div>
			</form>			
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="forgot_Model" role="dialog" ng-controller="FogotPassword">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" ng-click="resetForget()">&times;</button>
				<h4 class="modal-title">Forgot Password</h4>
			</div>
			<div class="modal-body">
				<form method="post" ng-submit="fogotPassword()" name="forgotForm"  ng-model="forgotForm">
					<div ng-show="success" class="alert alert-block alert-info fade in" style="text-align: center;">
						<button data-dismiss="alert" class="close close-sm" type="button">
							<i class="fa fa-times"></i>
						</button>
					{{successMsg}}</div>
					<div class="form-group">
						<input type="email" name="forgot_email" ng-model="forgot_email" ng-required="true" ng-pattern="formatEmail" class="form-control">
						<span class="error" ng-show="!forgotForm.forgot_email.$error.pattern && forgotForm.forgot_email.$error.required && forgotForm.forgot_email.$touched">Please Enter Email</span>          		
						<span class="error" ng-show="!forgotForm.forgot_email.$error.required && forgotForm.forgot_email.$invalid && forgotForm.email.$dirty && forgotForm.forgot_email.$touched">Please Enter Valid Email</span>          		
					</div>
					<div class="form-group">
						<button  type="submit" name="submit"  class="btn btn-primary" >Send</button>
						<button  type="reset" name="reset" ng-click="resetForget()"  class="btn btn-primary" data-dismiss="modal">Cancel</button>

					</div>
				</form>
			</div>
		</div>
	</div>
</div>