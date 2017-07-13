<?php
use app\assets\desktop\pub\PublicCommonAsset;

PublicCommonAsset::register($this);

/* @var $this yii\web\View */

$this->title = 'Login';

?>

<div class="create-deviser-account-wrapper pt-0" ng-controller="loginCtrl as loginCtrl">
	<form name="loginCtrl.form">
		<span class="login-title">Log in to your todevise account</span>
		<div class="create-deviser-account-container black-form" ng-if="!loginCtrl.loading">
			<div class="row">
				<label for="email">Email</label>
				<input type="email" id="email" name="email" ng-model="loginCtrl.user.email" class="form-control grey-input" />
			</div>
			<div class="row">
				<label for="password">Password</label>
				<input type="password" id="password" name="password" ng-model="loginCtrl.user.password" class="form-control grey-input" />
			</div>
			<div class="row">
				<div class="checkbox checkbox-circle remember-me">
					<input id="checkbox7" name="remember" ng-model="loginCtrl.user.rememberMe" class="styled" type="checkbox" value="1">
					<label for="checkbox7">
						Remember me
					</label>
				</div>
			</div>		
			<div class="alert alert-danger" ng-if="loginCtrl.errors" ng-cloak>Your email or password are not valid</div>
			<div class="row">
				<button type="submit" class="btn-red send-btn" ng-click="loginCtrl.login()">
					<i class="ion-android-navigate"></i>
				</button>
			</div>
		</div>
		<div class="text-center" ng-if="loginCtrl.loading">
			<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
			<span class="sr-only">Loading...</span>
		</div>
	</form>
</div>