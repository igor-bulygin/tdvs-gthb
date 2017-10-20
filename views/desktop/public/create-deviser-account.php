<?php

use app\assets\desktop\pub\PublicCommonAsset;
use app\models\Invitation;
use yii\helpers\Json;
use yii\helpers\Url;

PublicCommonAsset::register($this);

/** @var Invitation $invitation */

$this->title = Yii::t('app/public', 'CREATE_A_DEVISER_ACCOUNT');
Yii::$app->opengraph->title = $this->title;

$this->registerJs("var invitation = ".Json::encode($invitation), yii\web\View::POS_HEAD, 'invitation-var-script');
$this->registerJs("var type = 3", yii\web\View::POS_HEAD, 'person-type-var-script');

?>

<div class="create-deviser-account-wrapper">

	<img class="logo-md auto-center" src="/imgs/logo.svg" data-pin-nopin="true">
	<div class="white-title-lg text-center mt-40"><span translate="todevise.create_deviser.WELCOME"></span></div>
	<div class="white-subtitle-lg text-center"><span translate="todevise.create_deviser.START_CREATING"></span></div>

	<?php if (!$invitation) { ?>
	<div class="invitation-messages">
		<p><span translate="todevise.create_deviser.INVITATION_NEEDED"></span></p>
		<a href="<?= Url::to([" public/become-deviser "]) ?>" class="btn btn-red"><span translate="todevise.create_deviser.BECOME"></span></a>
	</div>
	<?php } elseif ($invitation->canUse()) { ?>
	<div class="create-deviser-account-container black-form" ng-controller="createAccountCtrl as createAccountCtrl">
		<form name="createAccountCtrl.form" novalidate>
			<div>
				<div class="row">
					<label for="email" translate="global.user.EMAIL"></label>
					<div class="input-check-wrapper">
						<input type="email" id="email" class="form-control grey-input ng-class:{'error-input': createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.email)}" name="email" ng-model="createAccountCtrl.person.email" required disabled="true">
						<i class="ion-checkmark" ng-if="createAccountCtrl.form.email.$valid" ng-cloak></i>
					</div>
					<form-errors field="createAccountCtrl.form.email" condition="createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.email)"></form-errors>
				</div>
				<div class="row">
					<label translate="global.user.SET_PASSWORD"></label>
					<div class="input-check-wrapper">
						<input type="password" id="email" class="form-control grey-input password ng-class:{'error-input':createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.password)}" name="password" ng-model="createAccountCtrl.person.password" ng-minlength="6" required>
						<i class="ion-checkmark" ng-if="createAccountCtrl.form.password.$valid" ng-cloak></i>
					</div>
					<form-errors field="createAccountCtrl.form.password" condition="createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.password)"></form-errors>
				</div>
				<div class="row">
					<label translate="global.user.REPEAT_PASSWORD"></label>
					<div class="input-check-wrapper">
						<input type="password" id="email" class="form-control grey-input password ng-class:{'error-input': createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.password_confirm) && createAccountCtrl.form.password_confirm.$error.same}" name="password_confirm" ng-model="createAccountCtrl.password_confirm" required>
						<i class="ion-checkmark" ng-if="!createAccountCtrl.form.password_confirm.$pristine && !createAccountCtrl.form.password_confirm.$error.same" ng-cloak></i>
					</div>
					<div ng-show="createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.password_confirm) && createAccountCtrl.form.password_confirm.$error.same" tdv-comparator value1="{{createAccountCtrl.person.password}}" value2="{{createAccountCtrl.password_confirm}}" result="createAccountCtrl.form.password_confirm.$error.same">
						<form-messages field="createAccountCtrl.form.password_confirm"></form-messages>
					</div>
				</div>
				<div class="row">
					<div class="checkbox checkbox-circle remember-me ng-class:{'error-input': createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.terms_and_conditions)}">
						<input id="checkbox7" class="styled" type="checkbox" name="terms_and_conditions" ng-model="createAccountCtrl.terms_and_conditions" required>
						<label for="checkbox7" translate="todevise.ACCEPT_TERMS"></label>
					</div>
					<form-errors field="createAccountCtrl.form.terms_and_conditions" condition="createAccountCtrl.has_error(createAccountCtrl.form, createAccountCtrl.form.terms_and_conditions)"></form-errors>
				</div>
			</div>
			<button class="btn-red send-btn" ng-click="createAccountCtrl.submitForm(createAccountCtrl.form)">
				<img src="/imgs/plane.svg" data-pin-nopin="true">
			</button>
		</form>
	</div>
	<?php } elseif ($invitation->isUsed()) { ?>
	<div class="invitation-messages">
		<p><span translate="todevise.create_deviser.INVITATION_USED"></span></p>
		<a href="<?= Url::to(["/public/login"]) ?>" class="btn btn-red"><span translate="global.LOGIN"></span></a>
	</div>
	<?php } else { ?>
	<div class="invitation-messages">
		<p><span translate="todevise.create_deviser.INVITATION_NOT_AVAILABLE"></span></p>
		<a href="<?= Url::to(["/public/become-deviser"]) ?>" class="btn btn-red"><span translate="todevise.create_deviser.BECOME"></span></a>
	</div>
	<?php } ?>
</div>