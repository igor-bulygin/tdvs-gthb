<?php

use app\assets\desktop\pub\PublicCommonAsset;

PublicCommonAsset::register($this);

$this->title = Yii::t('app/public', 'SIGNUP');
?>

<div class="create-deviser-account-wrapper">
	<div class="logo">
		<span class="title-create-account">
			<span class="first-title"><span translate="todevise.signup.WELCOME"></span></span>
			<span class="second-title"><span translate="todevise.signup.TO"></span></span>
		</span>
		<a class="image-create-account" href="/">
			<img src="/imgs/logo.png" data-pin-nopin="true">
		</a>
	</div>
	<div class="create-deviser-account-container black-form" ng-controller="createAccountCtrl as createAccountCtrl">
		<form name="createAccountCtrl.form" novalidate>
			<div>
				<div class="row">
					<label for="name"><span translate="todevise.signup.NAME"></span></label>
					<div class="input-check-wrapper">
						<input type="text" id="name" class="form-control grey-input ng-class:{'error-input': createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.name)}" name="name" ng-model="createAccountCtrl.person.name" required>
						<i class="ion-checkmark" ng-if="createAccountCtrl.form.name.$valid" ng-cloak></i>
					</div>
					<form-errors field="createAccountCtrl.form.name" condition="createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.name)"></form-errors>
				</div>
				<div class="row">
					<label for="last_name"><span translate="global.user.LAST_NAME"></span></label>
					<div class="input-check-wrapper">
						<input type="text" id="last_name" name="last_name" class="form-control grey-input ng-class:{'error-input': createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.last_name)}" ng-model="createAccountCtrl.person.last_name" required>
						<i class="ion-checkmark" ng-if="createAccountCtrl.form.last_name.$valid" ng-cloak></i>
					</div>
					<form-errors field="createAccountCtrl.form.last_name" condition="createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.last_name)"></form-errors>
				</div>
				<div class="row">
					<label for="email"><span translate="global.user.EMAIL"></span></label>
					<div class="input-check-wrapper">
						<input type="email" id="email" class="form-control grey-input ng-class:{'error-input': createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.email)}" name="email" ng-model="createAccountCtrl.person.email" required>
						<i class="ion-checkmark" ng-if="createAccountCtrl.form.email.$valid" ng-cloak></i>
					</div>
					<form-errors field="createAccountCtrl.form.email" condition="createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.email)"></form-errors>
				</div>
				<div class="row">
					<label for="password"><span translate="global.user.SET_PASSWORD"></span></label>
					<div class="input-check-wrapper">
						<input type="password" id="password" class="form-control grey-input password ng-class:{'error-input':createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.password)}" name="password" ng-model="createAccountCtrl.person.password" ng-minlength="6" required>
						<i class="ion-checkmark" ng-if="createAccountCtrl.form.password.$valid" ng-cloak></i>
					</div>
					<form-errors field="createAccountCtrl.form.password" condition="createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.password)"></form-errors>
				</div>
				<div class="row">
					<label for="password_confirm"><span translate="global.user.REPEAT_PASSWORD"></span></label>
					<div class="input-check-wrapper">
						<input type="password" id="password_confirm" class="form-control grey-input password ng-class:{'error-input': createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.password_confirm) && createAccountCtrl.form.password_confirm.$error.same}" name="password_confirm" ng-model="createAccountCtrl.password_confirm" required>
						<i class="ion-checkmark" ng-if="!createAccountCtrl.form.password_confirm.$pristine && !createAccountCtrl.form.password_confirm.$error.same" ng-cloak></i>
					</div>
					<div ng-show="createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.password_confirm) && createAccountCtrl.form.password_confirm.$error.same" tdv-comparator value1="{{createAccountCtrl.person.password}}" value2="{{createAccountCtrl.password_confirm}}" result="createAccountCtrl.form.password_confirm.$error.same">
						<form-messages field="createAccountCtrl.form.password_confirm"></form-messages>
					</div>
				</div>
				<div class="row">
					<div class="checkbox checkbox-circle remember-me ng-class:{'error-input': createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.terms_and_conditions)}">
						<input id="checkbox7" class="styled" type="checkbox" name="terms_and_conditions" ng-model="createAccountCtrl.terms_and_conditions" required>
						<label for="checkbox7">
							<span translate="todevise.ACCEPT_TERMS"></span>
						</label>
					</div>
					<form-errors field="createAccountCtrl.form.terms_and_conditions" condition="createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.terms_and_conditions)"></form-errors>
				</div>
			</div>
			<div ng-if="createAccountCtrl.error_message" class="text-center error-text" ng-cloak><p ng-bind="createAccountCtrl.error_message"></p></div>
			<button class="btn-red send-btn" ng-click="createAccountCtrl.submitForm(createAccountCtrl.form)">
				<i class="ion-android-navigate"></i>
			</button>
		</form>
	</div>
</div>