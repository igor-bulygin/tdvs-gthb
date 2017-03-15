<?php
use app\assets\desktop\pub\SignupAsset;

SignupAsset::register($this);

$this->title = 'Create an account - Todevise';

?>

<div class="create-deviser-account-wrapper">
	<div class="logo">
		<span class="title-create-account">
			<span class="first-title">welcome</span>
			<span class="second-title">to</span>
		</span>
		<a class="image-create-account" href="#">	º
			<img src="/imgs/logo.png" data-pin-nopin="true">
		</a>
	</div>
	<div class="create-deviser-account-container black-form" ng-controller="createClientCtrl as createClientCtrl">
		<form name="createClientCtrl.form" novalidate>
			<div>
				<div class="row">
					<label for="name">Name</label>
					<input type="text" id="name"" class="form-control grey-input ng-class:{'error-input': createClientCtrl.has_error(createClientCtrl.form, createClientCtrl.form.name)}" name="name" ng-model="createClientCtrl.user.name" required>
					<form-errors field="createClientCtrl.form.name" condition="createClientCtrl.has_error(createClientCtrl.form, createClientCtrl.form.name)"></form-errors>
				</div>
				<div class="row">
					<label for="email">Email</label>
					<input type="email" id="email" class="form-control grey-input ng-class:{'error-input': createClientCtrl.has_error(createClientCtrl.form, createClientCtrl.form.email)}" name="email" ng-model="createClientCtrl.user.email" required>
					<form-errors field="createClientCtrl.form.email" condition="createClientCtrl.has_error(createClientCtrl.form, createClientCtrl.form.email)"></form-errors>
				</div>
				<div class="row">
					<label for="password">Set your password</label>
					<input type="password" id="password" class="form-control grey-input password ng-class:{'error-input':createClientCtrl.has_error(createClientCtrl.form, createClientCtrl.form.password)}" name="password" ng-model="createClientCtrl.user.password" required>
					<form-errors field="createClientCtrl.form.password" condition="createClientCtrl.has_error(createClientCtrl.form, createClientCtrl.form.password)"></form-errors>
				</div>
				<div class="row">
					<label for="password_confirm">Repeat password</label>
					<input type="password" id="password_confirm" class="form-control grey-input password ng-class:{'error-input': createClientCtrl.has_error(createClientCtrl.form, createClientCtrl.form.password_confirm) && createClientCtrl.form.password_confirm.$error.same}" name="password_confirm" ng-model="createClientCtrl.password_confirm" required>
					<div ng-show="createClientCtrl.has_error(createClientCtrl.form, createClientCtrl.form.password_confirm) && createClientCtrl.form.password_confirm.$error.same" tdv-comparator value1="{{createClientCtrl.user.password}}" value2="{{createClientCtrl.password_confirm}}" result="createClientCtrl.form.password_confirm.$error.same">
						<form-messages field="createClientCtrl.form.password_confirm"></form-messages>
					</div>
				</div>
				<div class="row">
					<div class="checkbox checkbox-circle remember-me">
						<input id="checkbox7" class="styled ng-class:{'error-input': createClientCtrl.has_error(createClientCtrl.form, createClientCtrl.form.terms)}" type="checkbox" name="terms" ng-model="createClientCtrl.terms" required>
						<label for="checkbox7">
							I accept the Todevise Terms & Conditions
						</label>
					</div>
					<form-errors field="createClientCtrl.form.terms" condition="createClientCtrl.has_error(createClientCtrl.form, createClientCtrl.form.terms)"></form-errors>
				</div>
			</div>
			<div ng-if="createClientCtrl.error_message" class="text-center error-text" ng-cloak><p ng-bind="createClientCtrl.error_message"></p></div>
			<button class="btn-red send-btn" ng-click="createClientCtrl.submitForm(createClientCtrl.form)">
				<i class="ion-android-navigate"></i>
			</button>
		</form>
	</div>
</div>