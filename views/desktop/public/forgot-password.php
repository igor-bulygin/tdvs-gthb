<?php

use app\assets\desktop\pub\PublicCommonAsset;

PublicCommonAsset::register($this);

/* @var $this yii\web\View */

$this->title = Yii::t('app/public', 'FORGOT_PASSWORD');
Yii::$app->opengraph->title = $this->title;

?>

<div class="create-deviser-account-wrapper pt-0" ng-controller="loginCtrl as loginCtrl">
	<div ng-if="!loginCtrl.loading" ng-cloak>
		<form name="loginCtrl.forgotenPasswordForm">
			<span class="login-title" translate="todevise.forgot_password.TITLE"  ng-cloak></span>
			<div class="create-deviser-account-container black-form">
				<div class="row no-mar">
					<label for="email" translate="global.user.EMAIL"></label>
					<input type="email" id="email" name="email" ng-model="loginCtrl.resetPasswordEmail" class="form-control grey-input ng-class:{'error-input': loginCtrl.resetPasswordEmailRequired || loginCtrl.passwordEmailWrongFormat}"/>
					<div class="error-text" ng-if="loginCtrl.resetPasswordEmailRequired"><span translate="todevise.forgot_password.EMAIL_REQUIRED" ></span></div>
					<div class="error-text" ng-if="loginCtrl.passwordEmailWrongFormat"><span translate="todevise.forgot_password.EMAIL_fORMAT_ERROR" ></span></div>
				</div>
				<div ng-if="!loginCtrl.forgotPasswordSended" ng-cloak>
					<label translate="todevise.forgot_password.TEXT_INFO"></label>
				</div>
				<div ng-if="loginCtrl.forgotPasswordSended" ng-cloak>
					<label>succesfully sended</label>
				</div>
				<div class="row no-mar">
					<button type="submit" class="btn-red send-btn" ng-click="loginCtrl.askForResetPassword()">
						<img src="/imgs/plane.svg" data-pin-nopin="true">
					</button>
				</div>
			</div>
		</form>
	</div>
	<div class="text-center mt-30" ng-if="loginCtrl.loading" ng-cloak>
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
		<span class="sr-only" translate="global.LOADING"></span>
	</div>
</div>
