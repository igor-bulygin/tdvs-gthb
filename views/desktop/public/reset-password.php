<?php

use app\assets\desktop\pub\PublicCommonAsset;

PublicCommonAsset::register($this);

/* @var $this yii\web\View */
/* @var $person \app\models\Person */

$this->title = Yii::t('app/public', 'RESET_PASSWORD');
Yii::$app->opengraph->title = $this->title;
$this->registerJs("var person_id = '".$person_id."';", yii\web\View::POS_HEAD);
$this->registerJs("var action_id = '".$action_id."';", yii\web\View::POS_HEAD);
$this->registerJs("var resetEmail = '".$person->getEmail()."';", yii\web\View::POS_HEAD);

?>

<div class="create-deviser-account-wrapper pt-0" ng-controller="loginCtrl as loginCtrl">
	<form name="loginCtrl.resetForm">
		<span class="login-title" translate="todevise.reset_password.TITLE" ng-if="!loginCtrl.loading" ng-cloak></span>
		<?php if (!$person || !$valid) { ?>
			<div class="invitation-messages">
				<p><span translate="todevise.reset_password.INVALID"></span></p>
			</div>
		<?php } else {  ?>
			<div class="create-deviser-account-container black-form" >
				<div ng-if="!loginCtrl.loading" ng-cloak>
					<div ng-if="!loginCtrl.newPasswordSended">
						<div class="row no-mar">
							<label for="resetEmail" translate="global.user.EMAIL"></label>
							<input type="email" id="resetEmail" name="resetEmail" class="form-control grey-input" value="<?=$person->getEmail()?>" readonly disabled />
						</div>
						<div class="row no-mar">
							<label for="password" translate="global.user.NEW_PASSWORD"></label>
							<input type="password" id="password" name="password" class="form-control grey-input" ng-model="loginCtrl.newPassword"/>
							<div class="error-text" ng-if="loginCtrl.newPasswordRequired"><span translate="todevise.forgot_password.NEW_PASSWORD_REQUIRED" ></span></div>
						</div>
						<div class="row no-mar">
							<label for="repeat_password" translate="global.user.REPEAT_PASSWORD"></label>
							<input type="password" id="repeat_password" name="repeat_password" class="form-control grey-input" ng-model="loginCtrl.newRepeatedPassword" />
							<div class="error-text" ng-if="loginCtrl.newRepeatedPasswordRequired"><span translate="todevise.forgot_password.NEW_REPEATED_PASSWORD_REQUIRED" ></span></div>
							<div class="error-text" ng-if="loginCtrl.distinctPasswords"><span translate="todevise.forgot_password.DISTINCT_PASSWORDS" ></span></div>
						</div>
						<label translate="todevise.reset_password.TEXT_INFO"></label>
						<div class="row no-mar">
							<button type="submit" class="btn-red send-btn" ng-click="loginCtrl.resetPassword()">
								<img src="/imgs/plane.svg" data-pin-nopin="true">
							</button>
						</div>
					</div>
					<div ng-if="loginCtrl.newPasswordSended">
						<label translate="todevise.reset_password.SUCESSFULLY_RESET"></label>
					</div>
				</div>
				<div class="text-center mt-30" ng-if="loginCtrl.loading">
					<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
					<span class="sr-only" translate="global.LOADING"></span>
				</div>
			</div>
		<?php } ?>
	</form>
</div>
