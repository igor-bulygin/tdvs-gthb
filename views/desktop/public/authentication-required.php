<?php

use app\assets\desktop\pub\PublicCommonAsset;

PublicCommonAsset::register($this);

/* @var $this yii\web\View */

$this->title = 'Todevise: Login/Sign up';

?>

<div class="create-deviser-account-wrapper inverse pt-0" ng-controller="authenticationRequiredCtrl as authenticationRequiredCtrl">
	<div class="logo">
		<a class="image-create-account" href="/">
			<img src="/imgs/logo-red.svg" data-pin-nopin="true">
		</a>
	</div>
	<div class="mt-20 tdvs-loading" ng-if="authenticationRequiredCtrl.loading" ng-cloak>
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
		<span class="sr-only" translate="global.LOADING"></span>
	</div>
	<div ng-if="!authenticationRequiredCtrl.loading" ng-cloak>
		<div class="col-sm-12 col-md-6">
			<form name="authenticationRequiredCtrl.signForm">
				<span class="login-title" translate="todevise.authentication_required.NEW_SIGN"></span>
				<span class="already-user-mobile" translate="todevise.authentication_required.ALREADY_USER" ng-click="authenticationRequiredCtrl.goToLogin()"></span>
				<div class="create-deviser-account-container text-left">
					<div class="row">
						<label for="name" translate="global.user.FIRST_NAME"></label>
						<input type="text" name="name" ng-model="authenticationRequiredCtrl.user.name" class="form-control grey-input ng-class:{'error-input': authenticationRequiredCtrl.has_error(authenticationRequiredCtrl.signForm, authenticationRequiredCtrl.signForm.name)}" required>
						<form-errors field="authenticationRequiredCtrl.signForm.name" condition="authenticationRequiredCtrl.has_error(authenticationRequiredCtrl.signForm, authenticationRequiredCtrl.signForm.name)"></form-errors>
					</div>
					<div class="row">
						<label for="last_name" translate="global.user.LAST_NAME"></label>
						<input type="text" name="last_name" ng-model="authenticationRequiredCtrl.user.last_name" class="form-control grey-input ng-class:{'error-input': authenticationRequiredCtrl.has_error(authenticationRequiredCtrl.signForm, authenticationRequiredCtrl.signForm.last_name)}" required>
						<form-errors field="authenticationRequiredCtrl.signForm.last_name" condition="authenticationRequiredCtrl.has_error(authenticationRequiredCtrl.signForm, authenticationRequiredCtrl.signForm.last_name)"></form-errors>
					</div>
					<div class="row">
						<label for="email" translate="global.user.EMAIL"></label>
						<input type="email" name="email" ng-model="authenticationRequiredCtrl.user.email" class="form-control grey-input ng-class:{'error-input': authenticationRequiredCtrl.has_error(authenticationRequiredCtrl.signForm, authenticationRequiredCtrl.signForm.email)}" required>
						<form-errors field="authenticationRequiredCtrl.signForm.email" condition="authenticationRequiredCtrl.has_error(authenticationRequiredCtrl.signForm, authenticationRequiredCtrl.signForm.email)"></form-errors>
					</div>
					<div class="row">
						<label for="password" translate="global.user.PASSWORD"></label>
						<input type="password" name="password" ng-model="authenticationRequiredCtrl.user.password" class="form-control grey-input ng-class:{'error-input': authenticationRequiredCtrl.has_error(authenticationRequiredCtrl.signForm, authenticationRequiredCtrl.signForm.password)}" required>
						<form-errors field="authenticationRequiredCtrl.signForm.password" condition="authenticationRequiredCtrl.has_error(authenticationRequiredCtrl.signForm, authenticationRequiredCtrl.signForm.password)"></form-errors>
					</div>
					<div class="row">
						<label for="password_confirm" translate="global.user.REPEAT_PASSWORD"></label>
						<input type="password" id="password_confirm" name="password_confirm" ng-model="authenticationRequiredCtrl.password_confirm" class="form-control grey-input ng-class:{'error-input': authenticationRequiredCtrl.has_error(authenticationRequiredCtrl.signForm, authenticationRequiredCtrl.signForm.password_confirm) && authenticationRequiredCtrl.signForm.password_confirm.$error.same}" required>
						<div ng-show="authenticationRequiredCtrl.has_error(authenticationRequiredCtrl.signForm, authenticationRequiredCtrl.signForm.password_confirm) && authenticationRequiredCtrl.signForm.password_confirm.$error.same" tdv-comparator value1="{{authenticationRequiredCtrl.user.password}}" value2="{{authenticationRequiredCtrl.password_confirm}}" result="authenticationRequiredCtrl.signForm.password_confirm.$error.same">
							<form-messages field="authenticationRequiredCtrl.signForm.password_confirm"></form-messages>
						</div>
					</div>
					<div class="row">
						<label for="promo_code"><span translate="global.user.PROMO_CODE"></span></label><span class="optional-info-input" translate="global.user.OPTIONAL"></span>
						<div class="input-check-wrapper">
							<input type="text" id="promo_code" class="form-control grey-input" name="promo_code" ng-model="authenticationRequiredCtrl.user.parent_affiliate_id" ng-class="{true: 'error-input', false: ''}[authenticationRequiredCtrl.validCode == 'util.errors.PROMO_CODE_NOT_VALID']">
						</div>
						<div class="error-prom-code" ng-if="authenticationRequiredCtrl.validCode">
							<span translate="util.errors.PROMO_CODE_NOT_VALID"></span>
						</div>
					</div>
					<div class="row">
						<div class="checkbox checkbox-circle remember-me-black ng-class:{'error-input': authenticationRequiredCtrl.has_error(authenticationRequiredCtrl.signForm, authenticationRequiredCtrl.signForm.terms_and_conditions)}">
							<input id="terms_and_conditions" class="styled" type="checkbox" name="terms_and_conditions" ng-model="authenticationRequiredCtrl.user.terms_and_conditions" required>
							<label for="terms_and_conditions">
								<span translate="todevise.ACCEPT_TERMS"></span>
							</label>
						</div>
						<form-errors field="authenticationRequiredCtrl.signForm.terms_and_conditions" condition="authenticationRequiredCtrl.has_error(authenticationRequiredCtrl.signForm, authenticationRequiredCtrl.signForm.terms_and_conditions)"></form-errors>
					</div>
					<div class="row">
						<button type="submit" class="btn full-size-btn btn-red" ng-click="authenticationRequiredCtrl.signUp(authenticationRequiredCtrl.signForm)">
							<span translate="global.user.CREATE_AN_ACCOUNT"></span>
						</button>
					</div>
				</div>
			</form>
		</div>
		<div id="already-user-login" class="col-sm-12 col-md-6">
			<form name="authenticationRequiredCtrl.loginForm">
				<span class="login-title" translate="todevise.authentication_required.ALREADY_USER"></span>
				<div class="create-deviser-account-container text-left">
					<div class="row">
						<label for="email" translate="global.user.EMAIL"></label>
						<input type="email" id="email" name="email" ng-model="authenticationRequiredCtrl.login_user.email" class="form-control grey-input" required />
					</div>
					<div class="row">
						<label for="password" translate="global.user.PASSWORD"></label>
						<a class="link-red" href="<?=\yii\helpers\Url::to('/public/forgot-password')?>"><span translate="todevise.login.FORGOT_PASSWORD"></span></a>
						<input type="password" id="password" name="password" ng-model="authenticationRequiredCtrl.login_user.password" class="form-control grey-input" required />
					</div>
					<div class="row">
						<div class="checkbox checkbox-circle remember-me">
							<input id="checkbox7" name="remember" ng-model="authenticationRequiredCtrl.login_user.rememberMe" class="styled" type="checkbox" value="1">
							<label for="checkbox7" translate="todevise.authentication_required.REMEMBER"></label>
						</div>
					</div>
					<div class="alert alert-danger row" ng-if="authenticationRequiredCtrl.errorLogin" ng-cloak translate="todevise.login.NOT_VALID"></div>
					<div class="row">
						<button type="submit" class="btn full-size-btn btn-red" ng-click="authenticationRequiredCtrl.login(authenticationRequiredCtrl.loginForm)">
							<span translate="global.user.SIGN_IN"></span>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
