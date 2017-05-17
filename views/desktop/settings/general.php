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
				<form name="generalSettingsCtrl.dataForm" class="form-horizontal" >
					<div ng-hide="generalSettingsCtrl.saving">
						<div class="form-group">
							<label for="brand_name" class="col-md-2">Brand / Artist name</label>
							<div class="col-md-4">
								<input type="text" name="brand_name" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.brand_name" ng-class="{'purple': generalSettingsCtrl.dataForm.brand_name.$invalid}" required><!--only devisers -->
							</div>
							<label for="city" class="col-md-2">City</label>
							<div class="col-md-4">
								<input type="text" name="city" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.city" ng-class="{'purple': generalSettingsCtrl.dataForm.city.$invalid}" required><!-- with google API -->
							</div>
						</div>
						<div class="form-group">
							<label for="representative_name" class="col-md-2">Representative name</label>
							<div class="col-md-2">
								<input type="text" name="name" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.name" ng-class="{'purple': generalSettingsCtrl.dataForm.name.$invalid}">
							</div>
							<div class="col-md-2">
								<input type="text" name="last_name" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.last_name" ng-class="{'purple': generalSettingsCtrl.last.brand_name.$invalid}">
							</div>
							<label for="street" class="col-md-2">Street</label>
							<div class="col-md-4">
								<input type="text" name="street" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.street" ng-class="{'purple': generalSettingsCtrl.dataForm.street.$invalid}" required>
							</div>
						</div>
						<div class="form-group">
							<label for="phone" class="col-md-2">Phone number</label>
							<div class="col-md-4">
								<input type="text" name="phone" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.phone_number" ng-class="{'purple': generalSettingsCtrl.dataForm.phone.$invalid}" required>
							</div>
							<label for="number" class="col-md-2">Number</label>
							<div class="col-md-4">
								<input type="text" name="number" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.number" ng-class="{'purple': generalSettingsCtrl.dataForm.number.$invalid}" required>
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="col-md-2">Password</label>
							<div class="col-md-2">
								<input type="password" name="password" class="form-control" ng-class="{'purple': generalSettingsCtrl.dataForm.password.$invalid}" required>
							</div>
							<div class="col-md-2">
								<a href="#"><span class="glyphicon glyphicon-refresh"></span> Change password</a>
							</div>
							<label for="zip" class="col-md-2">ZIP</label>
							<div class="col-md-4">
								<input type="zip" name="zip" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.zip" ng-class="{'purple': generalSettingsCtrl.dataForm.zip.$invalid}" required>
							</div>
						</div>
						<div class="form-group">
							<label for="currency" class="col-md-2">Currency</label>
							<div class="col-md-4">
								<ol name="currency" class="col-md-12 nya-bs-select" ng-model="generalSettingsCtrl.person.settings.currency" ng-class="{'purple': generalSettingsCtrl.dataForm.currency.$invalid}">
									<li nya-bs-option="currency in generalSettingsCtrl.currencies" data-value="currency.value">
										<a href="#"><span ng-bind="currency.symbol"></span>&nbsp;<span ng-bind="currency.text"></span></a>
									</li>
								</ol>
							</div>
							<div class="col-md-6 text-right">
								<button class="btn btn-default btn-green" ng-click="generalSettingsCtrl.update()" ng-disabled="generalSettingsCtrl.saving">Save</button>
							</div>
						</div>
						<div class="col-xs-12 text-right text-green" ng-show="generalSettingsCtrl.saved&&!generalSettingsCtrl.dataForm.$dirty">
							<span>Updated changes</span>
						</div>
					</div>
				</form>
				<div class="text-center" ng-show="generalSettingsCtrl.saving">
					<img src="/imgs/loading.gif">
				</div>
			</div>
		</uib-accordion>
	</div>
</div>