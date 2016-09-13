<?php
use app\assets\desktop\pub\PublicCommonAsset;
use app\components\DeviserHeader;
use app\components\DeviserMenu;
use app\models\Person;
use app\models\Product;
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use app\assets\desktop\pub\Index2Asset;
use app\assets\desktop\pub\CreateDeviserAsset;

CreateDeviserAsset::register($this);

/** @var Person $deviser */

$this->title = 'Create a Deviser account - Todevise';

?>

	<div class="create-deviser-account-wrapper">
		<div class="logo">
			<img src="/imgs/logo.png" data-pin-nopin="true">
		</div>
		<div class="create-deviser-account-container black-form" ng-controller="createDeviserCtrl as createDeviserCtrl">
			<form name="createDeviserCtrl.form" novalidate>
				<div>
					<div class="row">
						<label for="email">Email address</label>
						<input type="email" id="email" class="form-control grey-input" name="email" ng-model="createDeviserCtrl.deviser.email" required>
						<form-errors field="createDeviserCtrl.form.email" condition="createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.email)"></form-errors>
					</div>
					<div class="row">
						<label>Brand name</label>
						<input type="text" class="form-control grey-input" name="brand_name" ng-model="createDeviserCtrl.deviser.brand_name" required>
						<form-errors field="createDeviserCtrl.form.brand_name" condition="createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.brand_name)"></form-errors>
					</div>
					<div class="row">
						<label>Representative name <i class="ion-information-circled info"></i></label>
						<input type="text" class="form-control grey-input" name="representative_name" ng-model="createDeviserCtrl.deviser.representative_name" required>
						<form-errors field="createDeviserCtrl.form.representative_name" condition="createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.representative_name)"></form-errors>
					</div>
					<div class="row">
						<label>Set your password</label>
						<input type="password" id="email" class="form-control grey-input password" name="password" ng-model="createDeviserCtrl.deviser.password" required>
						<form-errors field="createDeviserCtrl.form.password" condition="createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.password)"></form-errors>
					</div>
					<div class="row">
						<label>Repeat password</label>
						<input type="password" id="email" class="form-control grey-input password" name="password_confirm" ng-model="createDeviserCtrl.password_confirm" required>
						<div ng-show="createDeviserCtrl.has_error(createDeviserCtrl.form, createDeviserCtrl.form.password_confirm) && createDeviserCtrl.form.password_confirm.$error.same" tdv-comparator value1="{{createDeviserCtrl.deviser.password}}" value2="{{createDeviserCtrl.password_confirm}}" result="createDeviserCtrl.form.password_confirm.$error.same">
							<form-messages field="createDeviserCtrl.form.password_confirm"></form-messages>
						</div>
					</div>
				</div>
				<button class="btn-red send-btn" ng-click="createDeviserCtrl.submit(createDeviserCtrl.form)">
					<i class="ion-android-navigate"></i>
				</button>
			</form>
		</div>
	</div>