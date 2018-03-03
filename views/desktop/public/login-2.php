<?php

use app\assets\desktop\pub\PublicCommonAsset;
use yii\helpers\Url;

PublicCommonAsset::register($this);

/* @var $this yii\web\View */

$this->title = 'Login';
Yii::$app->opengraph->title = $this->title;

?>

<div class="create-deviser-account-wrapper pt-0" ng-controller="loginCtrl as loginCtrl">
	<form name="loginCtrl.form">
		<span class="login-title" translate="todevise.login.LOGIN_TITLE" ng-if="!loginCtrl.loading" ng-cloak></span>
		<div class="create-deviser-account-container black-form" ng-if="!loginCtrl.loading" ng-cloak>
			<div class="row no-mar">
				<label for="email" translate="global.user.EMAIL"></label>
				<input type="email" id="email" name="email" ng-model="loginCtrl.user.email" class="form-control grey-input" />
			</div>
			<div class="row no-mar">
				<label for="password" translate="global.user.PASSWORD"></label>
				<a class="link-red" href="<?=\yii\helpers\Url::to('/public/forgot-password')?>"><span translate="todevise.login.FORGOT_PASSWORD"></span></a>
				<input type="password" id="password" name="password" ng-model="loginCtrl.user.password" class="form-control grey-input" />
			</div>
			<div class="row no-mar">
				<div class="checkbox checkbox-circle remember-me">
					<input id="checkbox7" name="remember" ng-model="loginCtrl.user.rememberMe" class="styled" type="checkbox" value="1">
					<label for="checkbox7" translate="todevise.login.REMEMBER">

					</label>
				</div>
			</div>
			<div class="row mt-10 text-center">
				<span translate="todevise.login.NEW_TO_TODEVISE"></span> <a href="<?=Url::to(['/signup'])?>" translate="todevise.login.BECOME_A_MEMBER" class="text-red"></a> <span translate="todevise.login.ITS_100_FREE"></span>
			</div>
			<div class="alert alert-danger" ng-if="loginCtrl.errors" ng-cloak translate="todevise.login.NOT_VALID"></div>
			<div class="row no-mar">
				<button type="submit" class="btn-red send-btn" ng-click="loginCtrl.login()">
					<img src="/imgs/plane.svg" data-pin-nopin="true">
				</button>
			</div>
		</div>
		<div class="mt-30 tdvs-loading" ng-if="loginCtrl.loading">
			<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
			<span class="sr-only" translate="global.LOADING"></span>
		</div>
	</form>
</div>
