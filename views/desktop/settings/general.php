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
							<div class="col-md-4 text-right">
								<input type="text" name="brand_name" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.brand_name" ng-class="{'error-input': generalSettingsCtrl.dataForm.brand_name.$invalid}" required><!--only devisers -->
								<span class="purple-text" ng-show="generalSettingsCtrl.existRequiredError('brand_name')">Please, fill out this field</span>
							</div>
							<label for="city" class="col-md-2">City</label>
							<div class="col-md-4 text-right">
								<input type="text" name="city" class="form-control" ng-model="generalSettingsCtrl.city" ng-class="{'error-input': generalSettingsCtrl.dataForm.city.$invalid}" ng-model-options='{ debounce: 100 }' ng-change="generalSettingsCtrl.searchPlace(generalSettingsCtrl.person.personal_info.city)" required>
								<div ng-if="generalSettingsCtrl.showCities" ng-cloak>
									<ul class="city-selection">
										<li ng-repeat="city in generalSettingsCtrl.cities">
											<span ng-click="generalSettingsCtrl.selectCity(city)" style="cursor:pointer;">{{city.city}} - {{city.country_name}}</span>
										</li>
										<li>
											<img class="powered-google" src="/imgs/powered_by_google_on_white_hdpi.png">
										</li>
									</ul>
								</div>
								<span class="purple-text" ng-show="generalSettingsCtrl.existRequiredError('city')">Please, fill out this field</span>
							</div>
						</div>

						<div class="form-group">
							<label for="representative_name" class="col-md-2">Representative name</label>
							<div class="col-md-2">
								<input type="text" name="name" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.name" ng-class="{'error-input': generalSettingsCtrl.dataForm.name.$invalid}">								
							</div>
							<div class="col-md-2 text-right">
								<input type="text" name="last_name" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.last_name" ng-class="{'error-input': generalSettingsCtrl.last_name.$invalid}">
								<span class="text-green">OPTIONAL</span>
							</div>
							<label for="street" class="col-md-2">Street</label>
							<div class="col-md-4 text-right">
								<input type="text" name="street" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.street" ng-class="{'error-input': generalSettingsCtrl.dataForm.street.$invalid}" required>
								<span class="purple-text" ng-show="generalSettingsCtrl.existRequiredError('street')">Please, fill out this field</span>
							</div>
						</div>
						<div class="form-group">
							<label for="phone" class="col-md-2">Phone number</label>
							<div class="col-md-4 text-right">
								<input type="tel" name="phone" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.phone_number" ng-class="{'error-input': generalSettingsCtrl.dataForm.phone.$invalid}" required>
								<span class="purple-text" ng-show="generalSettingsCtrl.existRequiredError('phone')">Please, fill out this field</span>
							</div>
							<label for="number" class="col-md-2">Number</label>
							<div class="col-md-4 text-right">
								<input type="text" name="number" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.number" ng-class="{'error-input': generalSettingsCtrl.dataForm.number.$invalid}" required>
								<span class="purple-text" ng-show="generalSettingsCtrl.existRequiredError('number')">Please, fill out this field</span>
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="col-md-2">Password</label>
							<div class="col-md-2 text-right">
								<input type="password" name="password" class="form-control" ng-class="{'error-input': generalSettingsCtrl.dataForm.password.$invalid}" placeholder="*********" disabled>
							</div>
							<div class="col-md-2">
								<a href="#" class="red-text"><span class="glyphicon glyphicon-refresh"></span> Change password</a>
							</div>
							<label for="zip" class="col-md-2">ZIP</label>
							<div class="col-md-4 text-right">
								<input type="text" name="zip" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.zip" ng-class="{'error-input': generalSettingsCtrl.dataForm.zip.$invalid}" required>
								<span class="purple-text" ng-show="generalSettingsCtrl.existRequiredError('zip')">Please, fill out this field</span>
							</div>
						</div>
						<div class="form-group">
							<label for="currency" class="col-md-2">Currency</label>
							<div class="col-md-4 text-right">
								<ol name="currency" class="col-md-12 nya-bs-select" ng-model="generalSettingsCtrl.person.settings.currency" ng-class="{'error-input': generalSettingsCtrl.notCurrencySelected}">
									<li nya-bs-option="currency in generalSettingsCtrl.currencies" data-value="currency.value">
										<a href="#"><span ng-bind="currency.symbol"></span>&nbsp;<span ng-bind="currency.text"></span></a>
									</li>
								</ol>
								<span class="purple-text" ng-show="generalSettingsCtrl.notCurrencySelected">Please, select an option</span>
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