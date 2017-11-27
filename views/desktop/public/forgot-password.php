<?php

use app\assets\desktop\pub\PublicCommonAsset;

PublicCommonAsset::register($this);

/* @var $this yii\web\View */

$this->title = Yii::t('app/public', 'FORGOT_PASSWORD');
Yii::$app->opengraph->title = $this->title;

?>

<div class="create-deviser-account-wrapper pt-0" ng-controller="loginCtrl as loginCtrl">
	<form name="loginCtrl.form">
		<span class="login-title" translate="todevise.forgot_password.TITLE" ng-if="!loginCtrl.loading" ng-cloak></span>
		<div class="create-deviser-account-container black-form" ng-if="!loginCtrl.loading" ng-cloak>
			<div class="row no-mar">
				<label for="email" translate="global.user.EMAIL"></label>
				<input type="email" id="email" name="email" ng-model="loginCtrl.user.email" class="form-control grey-input" />
			</div>
			<div class="alert alert-danger" ng-if="loginCtrl.errors" ng-cloak translate="todevise.login.NOT_VALID"></div>
			<span style="color: white;" translate="todevise.forgot_password.TEXT_INFO"></span>
			<div class="row no-mar">
				<button type="submit" class="btn-red send-btn" ng-click="loginCtrl.login()">
					<img src="/imgs/plane.svg" data-pin-nopin="true">
				</button>
			</div>
		</div>
		<div class="text-center mt-30" ng-if="loginCtrl.loading">
			<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
			<span class="sr-only" translate="global.LOADING"></span>
		</div>
	</form>
</div>
