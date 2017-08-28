<?php

use app\assets\desktop\pub\PublicCommonAsset;
use app\models\Invitation;
use yii\helpers\Url;

PublicCommonAsset::register($this);

/** @var Invitation $invitation */

$this->title = Yii::t('app/public', 'Create an influencer account - Todevise');

$this->registerJs("var invitation = ".\yii\helpers\Json::encode($invitation), yii\web\View::POS_HEAD, 'invitation-var-script');
$this->registerJs("var type = 2", yii\web\View::POS_HEAD, 'person-type-var-script');

?>


<div class="create-deviser-account-wrapper">
	<div class="logo">
		<span class="title-create-account">
			<span class="first-title">welcome</span>
			<span class="second-title">to</span>
		</span>
		<a class="image-create-account" href="#">
			<img src="/imgs/logo.png" data-pin-nopin="true">
		</a>
	</div>
	<?php if (!$invitation) { ?>
		<div class="invitation-messages">
			<p>You need an invitation to create an account. You can ask for one in "Become a Deviser".</p>
			<a href="<?= Url::to([" public/become-deviser "]) ?>" class="btn btn-red">Become a Deviser</a>
		</div>
	<?php } elseif ($invitation->canUse()) { ?>
		<div class="create-deviser-account-container black-form" ng-controller="createAccountCtrl as createAccountCtrl">
			<form name="createAccountCtrl.form" novalidate>
				<div>
					<div class="row">
						<label for="email">Email address</label>
						<div class="input-check-wrapper">
							<input type="email" id="email" class="form-control grey-input ng-class:{'error-input': createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.email)}" name="email" ng-model="createAccountCtrl.person.email" required disabled="true">
							<i class="ion-checkmark" ng-if="createAccountCtrl.form.email.$valid" ng-cloak></i>
						</div>
						<form-errors field="createAccountCtrl.form.email" condition="createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.email)"></form-errors>
					</div>
					<div class="row">
						<label for="password">Set your password</label>
						<div class="input-check-wrapper">
							<input type="password" id="password" class="form-control grey-input password ng-class:{'error-input':createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.password)}" name="password" ng-model="createAccountCtrl.person.password" ng-minlength="6" required>
							<i class="ion-checkmark" ng-if="createAccountCtrl.form.password.$valid" ng-cloak></i>
						</div>
						<form-errors field="createAccountCtrl.form.password" condition="createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.password)"></form-errors>
					</div>
					<div class="row">
						<label for="password_confirm">Repeat password</label>
						<div class="input-check-wrapper">
							<input type="password" id="password_confirm" class="form-control grey-input password ng-class:{'error-input': createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.password_confirm) && createAccountCtrl.form.password_confirm.$error.same}" name="password_confirm" ng-model="createAccountCtrl.password_confirm" required>
							<i class="ion-checkmark" ng-if="!createAccountCtrl.form.password_confirm.$pristine && !createAccountCtrl.form.password_confirm.$error.same"></i>
						</div>
						<div ng-show="createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.password_confirm) && createAccountCtrl.form.password_confirm.$error.same" tdv-comparator value1="{{createAccountCtrl.person.password}}" value2="{{createAccountCtrl.password_confirm}}" result="createAccountCtrl.form.password_confirm.$error.same">
							<form-messages field="createAccountCtrl.form.password_confirm"></form-messages>
						</div>
					</div>
					<div class="row">
						<div class="checkbox checkbox-circle remember-me ng-class:{'error-input': createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.terms_and_conditions)}">
							<input id="checkbox7" class="styled" type="checkbox" name="terms_and_conditions" ng-model="createAccountCtrl.terms_and_conditions" required>
							<label for="checkbox7">
								I accept the Todevise Terms & Conditions
							</label>
						</div>
						<form-errors field="createAccountCtrl.form.terms_and_conditions" condition="createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.terms_and_conditions)"></form-errors>
					</div>
				</div>
				<button class="btn-red send-btn" ng-click="createAccountCtrl.submitForm(createAccountCtrl.form)">
					<i class="ion-android-navigate"></i>
				</button>
			</form>
		</div>
	<?php } elseif ($invitation->isUsed()) { ?>
		<div class="invitation-messages">
			<p>You have created an Influencer account with this invitation. Login to access to your account.</p>
			<a href="<?= Url::to(["/public/login"]) ?>" class="btn btn-red">Login</a>
		</div>
	<?php } else { ?>
		<div class="invitation-messages">
			<p>This invitation is not longer available. You can ask for a new one in "Become a Deviser".</p>
			<a href="<?= Url::to(["/public/become-deviser"]) ?>" class="btn btn-red">Become a Deviser</a>
		</div>
	<?php } ?>
</div>