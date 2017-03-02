<?php
use app\assets\desktop\pub\CreateDeviserAsset;
use app\models\Invitation;
use yii\helpers\Json;
use yii\helpers\Url;

CreateDeviserAsset::register($this);

/** @var Invitation $invitation */

$this->title = 'Create a Deviser account - Todevise';
$this->registerJs("var invitation = ".Json::encode($invitation), yii\web\View::POS_HEAD, 'invitation-var-script');

?>

	<div class="create-deviser-account-wrapper">
		<div class="logo">
			<a href="#">
				<img src="/imgs/logo.png" data-pin-nopin="true">
			</a>
		</div>
		<?php if (!$invitation) { ?>
			<div class="invitation-messages">
				<p>You need an invitation to create an account. You can ask for one in "Become a Deviser".</p>
				<a href="<?= Url::to([" public/become-deviser "]) ?>" class="btn btn-red">Become a Deviser</a>
			</div>
			<?php } elseif ($invitation->canUse()) { ?>
				<div class="create-deviser-account-container black-form" ng-controller="createDeviserCtrl as createDeviserCtrl">
					<form name="createDeviserCtrl.form" novalidate>
						<div>
							<div class="row">
								<label for="email">Email address</label>
								<input type="email" id="email" class="form-control grey-input ng-class:{'error-input': createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.email)}" name="email" ng-model="createDeviserCtrl.deviser.email" required disabled="true">
								<form-errors field="createDeviserCtrl.form.email" condition="createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.email)"></form-errors>
							</div>
							<div class="row">
								<label>Brand name</label>
								<input type="text" class="form-control grey-input ng-class:{'error-input': createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.brand_name)}" name="brand_name" ng-model="createDeviserCtrl.deviser.brand_name" required>
								<form-errors field="createDeviserCtrl.form.brand_name" condition="createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.brand_name)"></form-errors>
							</div>
							<div class="row">
								<label>Representative name <i tooltip-placement="top" uib-tooltip="This name will not be made public. We will only use it to communicate with you." class="ion-information-circled info"></i></label>
								<input type="text" class="form-control grey-input ng-class:{'error-input': createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.first_name)}" name="first_name" ng-model="createDeviserCtrl.deviser.first_name" placeholder="FIRST NAME" required>
								<form-errors field="createDeviserCtrl.form.first_name" condition="createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.first_name)"></form-errors>
							</div>
							<div class="row">
								<input type="text" class="form-control grey-input ng-class:{'error-input': createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.last_name)}" name="last_name" ng-model="createDeviserCtrl.deviser.last_name" placeholder="LAST NAME" required>
								<form-errors field="createDeviserCtrl.form.last_name" condition="createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.last_name)"></form-errors>
							</div>
							<div class="row">
								<label>Set your password</label>
								<input type="password" id="email" class="form-control grey-input password ng-class:{'error-input':createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.password)}" name="password" ng-model="createDeviserCtrl.deviser.password" required>
								<form-errors field="createDeviserCtrl.form.password" condition="createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.password)"></form-errors>
							</div>
							<div class="row">
								<label>Repeat password</label>
								<input type="password" id="email" class="form-control grey-input password ng-class:{'error-input': createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.password_confirm) && createDeviserCtrl.form.password_confirm.$error.same}" name="password_confirm" ng-model="createDeviserCtrl.password_confirm" required>
								<div ng-show="createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.password_confirm) && createDeviserCtrl.form.password_confirm.$error.same" tdv-comparator value1="{{createDeviserCtrl.deviser.password}}" value2="{{createDeviserCtrl.password_confirm}}" result="createDeviserCtrl.form.password_confirm.$error.same">
									<form-messages field="createDeviserCtrl.form.password_confirm"></form-messages>
								</div>
							</div>
							<div class="row">
								<div class="checkbox checkbox-circle remember-me">
									<input id="checkbox7" class="styled" type="checkbox">
									<label for="checkbox7">
										Remember me
									</label>
								</div>
							</div>
						</div>
						<button class="btn-red send-btn" ng-click="createDeviserCtrl.submitForm(createDeviserCtrl.form)">
							<i class="ion-android-navigate"></i>
						</button>
					</form>
				</div>
				<?php } elseif ($invitation->isUsed()) { ?>
					<div class="invitation-messages">
						<p>You have created a Deviser account with this invitation. Login to access to your account.</p>
						<a href="<?= Url::to([" public/index "]) ?>" class="btn btn-red">Login</a>
					</div>
					<?php } else { ?>
						<div class="invitation-messages">
							<p>This invitation is not longer available. You can ask for a new one in "Become a Deviser".</p>
							<a href="<?= Url::to([" public/become-deviser "]) ?>" class="btn btn-red">Become a Deviser</a>
						</div>
						<?php } ?>
	</div>