<?php
use app\assets\desktop\pub\CreateInfluencerAsset;
use app\models\Invitation;
use yii\helpers\Url;

CreateInfluencerAsset::register($this);

/** @var Invitation $invitation */

$this->title = 'Create an influencer account - Todevise';
$this->registerJs("var invitation = ".\yii\helpers\Json::encode($invitation), yii\web\View::POS_HEAD, 'invitation-var-script');

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
		<div class="create-deviser-account-container black-form" ng-controller="createInfluencerCtrl as createInfluencerCtrl">
			<form name="createInfluencerCtrl.form" novalidate>
				<div>
					<div class="row">
						<label for="email">Email address</label>
						<input type="email" id="email" class="form-control grey-input ng-class:{'error-input': createInfluencerCtrl.has_error(createInfluencerCtrl.form, createInfluencerCtrl.form.email)}" name="email" ng-model="createInfluencerCtrl.influencer.email" required disabled="true">
						<form-errors field="createInfluencerCtrl.form.email" condition="createInfluencerCtrl.has_error(createInfluencerCtrl.form, createInfluencerCtrl.form.email)"></form-errors>
					</div>
					<div class="row">
						<label for="password">Set your password</label>
						<input type="password" id="password" class="form-control grey-input password ng-class:{'error-input':createInfluencerCtrl.has_error(createInfluencerCtrl.form, createInfluencerCtrl.form.password)}" name="password" ng-model="createInfluencerCtrl.influencer.password" ng-minlength="6" required>
						<form-errors field="createInfluencerCtrl.form.password" condition="createInfluencerCtrl.has_error(createInfluencerCtrl.form, createInfluencerCtrl.form.password)"></form-errors>
					</div>
					<div class="row">
						<label for="password_confirm">Repeat password</label>
						<input type="password" id="password_confirm" class="form-control grey-input password ng-class:{'error-input': createInfluencerCtrl.has_error(createInfluencerCtrl.form, createInfluencerCtrl.form.password_confirm) && createInfluencerCtrl.form.password_confirm.$error.same}" name="password_confirm" ng-model="createInfluencerCtrl.password_confirm" required>
						<div ng-show="createInfluencerCtrl.has_error(createInfluencerCtrl.form, createInfluencerCtrl.form.password_confirm) && createInfluencerCtrl.form.password_confirm.$error.same" tdv-comparator value1="{{createInfluencerCtrl.influencer.password}}" value2="{{createInfluencerCtrl.password_confirm}}" result="createInfluencerCtrl.form.password_confirm.$error.same">
							<form-messages field="createInfluencerCtrl.form.password_confirm"></form-messages>
						</div>
					</div>
					<div class="row">
						<div class="checkbox checkbox-circle remember-me ng-class:{'error-input': createInfluencerCtrl.has_error(createInfluencerCtrl.form, createInfluencerCtrl.form.terms_and_conditions)}">
							<input id="checkbox7" class="styled" type="checkbox" name="terms_and_conditions" ng-model="createInfluencerCtrl.terms_and_conditions" required>
							<label for="checkbox7">
								I accept the Todevise Terms & Conditions
							</label>
						</div>
						<form-errors field="createInfluencerCtrl.form.terms_and_conditions" condition="createInfluencerCtrl.has_error(createInfluencerCtrl.form, createInfluencerCtrl.form.terms_and_conditions)"></form-errors>
					</div>
				</div>
				<button class="btn-red send-btn" ng-click="createInfluencerCtrl.submitForm(createInfluencerCtrl.form)">
					<i class="ion-android-navigate"></i>
				</button>
			</form>
		</div>
	<?php } elseif ($invitation->isUsed()) { ?>
		<div class="invitation-messages">
			<p>You have created an Influencer account with this invitation. Login to access to your account.</p>
			<a href="<?= Url::to([" public/index "]) ?>" class="btn btn-red">Login</a>
		</div>
	<?php } else { ?>
		<div class="invitation-messages">
			<p>This invitation is not longer available. You can ask for a new one in "Become a Deviser".</p>
			<a href="<?= Url::to([" public/become-deviser "]) ?>" class="btn btn-red">Become a Deviser</a>
		</div>
	<?php } ?>
</div>