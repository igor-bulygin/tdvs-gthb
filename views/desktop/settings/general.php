<?php
use app\assets\desktop\settings\GeneralSettingsAsset;
use app\components\SettingsHeader;
use app\models\Person;
use yii\helpers\Json;
use yii\helpers\Url;

GeneralSettingsAsset::register($this);

/** @var Person $person */

$this->title = 'Settings - ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['settings_menu_active_option'] = 'general';
$this->registerJs("var person= ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

<?= SettingsHeader::widget() ?>

<div ng-controller="generalSettingsCtrl as generalSettingsCtrl" class="personal-info-wrapper bank-settings-wrapper">
	<div class="container">
		<uib-accordion>
			<div uib-accordion-group class="panel-default panel-billing" heading="Personal information" is-open="true" ng-cloak>
				<form name="generalSettingsCtrl.form" class="form-horizontal">
					<div class="form-group">
						<label for="brand_name" class="col-md-2">Brand / Artist name</label>
						<div class="col-md-4">
							<input type="text" name="brand_name" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.brand_name">
						</div>
						<label for="city" class="col-md-2">City</label>
						<div class="col-md-4">
							<input type="text" name="city" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.city">
						</div>
					</div>
					<div class="form-group">
						<label for="representative_name" class="col-md-2">Representative name</label>
						<div class="col-md-4">
							<input type="text" name="representative_name" class="form-control" ng-model="generalSettingsCtrl.person.settings.representative_name">
						</div>
						<label for="street" class="col-md-2">Street</label>
						<div class="col-md-4">
							<input type="text" name="street" class="form-control" ng-model="generalSettingsCtrl.person.settings.street">
						</div>
					</div>
					<div class="form-group">
						<label for="phone" class="col-md-2">Phone number</label>
						<div class="col-md-4">
							<input type="text" name="phone" class="form-control" ng-model="generalSettingsCtrl.person.settings.phone_number">
						</div>
						<label for="number" class="col-md-2">Number</label>
						<div class="col-md-4">
							<input type="text" name="number" class="form-control" ng-model="generalSettingsCtrl.person.settings.number">
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-md-2">Password</label>
						<div class="col-md-2">
							<input type="password" name="password" class="form-control">
						</div>
						<div class="col-md-2">
							<a href="#"><span class="glyphicon glyphicon-refresh"></span> Change password</a>
						</div>
						<label for="zip" class="col-md-2">ZIP</label>
						<div class="col-md-4">
							<input type="zip" name="zip" class="form-control" ng-model="generalSettingsCtrl.person.settings.zip">
						</div>
					</div>
					<div class="form-group">
						<label for="currency" class="col-md-2">Currency</label>
						<ol class="nya-bs-select" ng-model="generalSettingsCtrl.person.settings.currency">
							<li nya-bs-option="currency in generalSettingsCtrl.currencies" data-value="currency.value">
								<a href="#"><span ng-bind="currency.symbol"></span>&nbsp;<span ng-bind="currency.text"></span></a>
							</li>
						</ol>
					</div>
				</form>
			</div>
		</uib-accordion>
	</div>
</div>