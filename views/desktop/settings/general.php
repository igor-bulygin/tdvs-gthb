<?php

use app\assets\desktop\settings\GlobalAsset;
use app\components\SettingsHeader;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = Yii::t('app/public', 'Settings');

$this->params['person'] = $person;
$this->params['settings_menu_active_option'] = 'general';
$this->registerJs("var person= ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

<?php if($person->isCompletedProfile()) { ?>
	<?= SettingsHeader::widget() ?>
<?php } ?>

<div ng-controller="generalSettingsCtrl as generalSettingsCtrl" class="personal-info-wrapper bank-settings-wrapper">
	<div class="container">
		<uib-accordion>
			<div uib-accordion-group class="panel-default panel-billing" heading="{{ 'PERSONAL_INFORMATION' | translate }}" is-open="true" ng-cloak>
				<form name="generalSettingsCtrl.dataForm" class="form-horizontal" >
					<div ng-hide="generalSettingsCtrl.saving">
						<div class="form-group">
							<div ng-if="generalSettingsCtrl.isDeviser">
								<label for="brand_name" class="col-md-2" translate="BRAND"></label>
								<div class="col-md-4 text-right">
									<input type="text" name="brand_name" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.brand_name" ng-class="{'error-input': generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.brand_name)}"><!--only devisers -->
									<span class="purple-text" ng-if="generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.brand_name)" translate="FILL_FIELD"></span>
								</div>
							</div>
							<label for="vat_id" class="col-md-2" translate="IDENTIFIER"></label>
							<div class="col-md-4 text-right">
								<input type="text" name="vat_id" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.vat_id" ng-class="{'error-input': generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.vat_id)}">
								<span class="purple-text" ng-if="generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.vat_id)" translate="FILL_FIELD"></span>
							</div>
						</div>
						<div class="form-group">
							<div class=col-xs-2>
								<label for="representative_name" translate="REPRESENTATIVE_NAME"></label>
								<span class="col-xs-12 text-green" translate="OPTIONAL"></span>
							</div>
							<div class="col-md-2">
								<input type="text" name="name" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.name" placeholder="{{ 'FIRST_NAME' | translate }}">
							</div>
							<div class="col-md-2 text-right">
								<input type="text" name="last_name" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.last_name" placeholder="{{ 'LAST_NAME' | translate }}">
							</div>
							<label for="city" class="col-md-2" ng-class="{'col-md-offset-6': !generalSettingsCtrl.isDeviser}">CITY</label>
							<div class="col-md-4 text-right">
								<input type="text" name="city" class="form-control" ng-model="generalSettingsCtrl.city" ng-class="{'error-input': generalSettingsCtrl.existRequiredError(generalSettingsCtrl.city)}" ng-model-options='{ debounce: 80 }' ng-change="generalSettingsCtrl.searchPlace(generalSettingsCtrl.city)">
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
								<span class="purple-text" ng-if="generalSettingsCtrl.existRequiredError(generalSettingsCtrl.city)" translate="FILL_FIELD"></span>
							</div>
						</div>
						<div class="form-group">
							<label for="phone" class="col-md-2" translate="PHONE"></label>
							<div class="col-md-1 text-right">
								<input type="tel" name="phone_prefix" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.phone_number_prefix" ng-class="{'error-input': generalSettingsCtrl.invalidPrefix }" ng-change="generalSettingsCtrl.setPrefix()">
							</div>
							<div class="col-md-3 text-right">
								<input type="tel" name="phone" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.phone_number" ng-class="{'error-input': generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.phone_number)}">
								<span class="purple-text" ng-if="generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.phone_number)" translate="FILL_FIELD"></span>
							</div>
							<label for="address" class="col-md-2" translate="ADDRESS"></label>
							<div class="col-md-4 text-right">
								<input type="text" name="address" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.address" ng-class="{'error-input': generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.address)}">
								<span class="purple-text" ng-if="generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.address)" translate="FILL_FIELD"></span>
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="col-md-2" translate="PASSWORD"></label>
							<div class="col-md-2 text-right">
								<input type="password" name="password" class="form-control" placeholder="*********" disabled>
							</div>
							<div class="col-md-2">
								<a href="#" class="red-text" ng-click="generalSettingsCtrl.openModal()" translate="CHANGE_PASSWORD"><span class="glyphicon glyphicon-refresh"></span></a>
							</div>
							<label for="zip" class="col-md-2" translate="ZIP"></label>
							<div class="col-md-4 text-right">
								<input type="text" name="zip" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.zip" ng-class="{'error-input': generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.zip)}">
								<span class="purple-text" ng-if="generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.zip)" translate="FILL_FIELD"></span>
							</div>
						</div>
						<div class="form-group">
						<label for="weight_measure" class="col-md-2" translate="WEIGHT_MEASURE"></label>
							<div class="col-md-4" >
								<ol name="weightMeasure" class="nya-bs-select" ng-model="generalSettingsCtrl.person.settings.weight_measure" ng-class="{'error-input': generalSettingsCtrl.notWeightMeasureSelected}" ng-change="generalSettingsCtrl.notWeightMeasureSelected=false" ng-show="generalSettingsCtrl.weightCharged">
									<li nya-bs-option="weightMeasure in generalSettingsCtrl.weightMeasures">
										<a href="#"><span ng-bind="weightMeasure"></span></a>
									</li>
								</ol>
								<span class="purple-text col-xs-12" ng-if="generalSettingsCtrl.showInvalid && generalSettingsCtrl.notWeightMeasureSelected" translate="SELECT_MEASUREMENT"></span>
							</div>
							<div class="col-md-6 text-right">
								<button class="btn btn-default btn-green col-md-offset-10 col-md-2" ng-click="generalSettingsCtrl.update()" ng-disabled="generalSettingsCtrl.saving" translate="SAVE"></button>
								<span class="purple-text col-xs-12" ng-if="generalSettingsCtrl.showInvalid" translate="FILL_ALL_FIELDS"></span>
							</div>
						</div>
						<div class="col-xs-12 text-center" ng-if="generalSettingsCtrl.saved&&!generalSettingsCtrl.dataForm.$dirty">
							<span translate="CHANGES_SAVED"><i class="ion-checkmark text-green"></i></span>
						</div>
					</div>
				</form>
				<div class="text-center" ng-show="generalSettingsCtrl.saving">
					<img src="/imgs/loading.gif">
				</div>
			</div>
		</uib-accordion>
	</div>
	<script type="text/ng-template" id="passwordModal">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="generalSettingsCtrl.dismiss()">
			<span class="ion-ios-close" aria-hidden="true"></span>
		</button>
			<h3 class="modal-title" id="modal-title" translate="CHANGE_PASSWORD"></h3>
		</div>
		<div class="modal-body personal-info-wrapper">
			<form name="generalSettingsCtrl.passwordForm" class="form-horizontal col-xs-12">
				<div ng-hide="generalSettingsCtrl.savingPassword">
					<div class="col-xs-10 col-xs-offset-1">
						<input type="password" name="currentPassword" class="form-control" ng-class="{'error-input': generalSettingsCtrl.existPasswordRequiredError(generalSettingsCtrl.currentPassword)}" ng-model="generalSettingsCtrl.currentPassword" placeholder="{{'CURRENT_PASSWORD' | translate }}">
						<span class="purple-text" ng-if="generalSettingsCtrl.existPasswordRequiredError(generalSettingsCtrl.currentPassword)" translate="FILL_FIELD"></span>
					</div>
					<div class="col-xs-10 col-xs-offset-1">
						<input type="password" name="newPassword" class="form-control" ng-class="{'error-input': generalSettingsCtrl.existPasswordRequiredError(generalSettingsCtrl.newPassword)}" ng-model="generalSettingsCtrl.newPassword" placeholder="{{'NEW_PASSWORD' | translate }}">
						<span class="purple-text" ng-if="generalSettingsCtrl.existPasswordRequiredError(generalSettingsCtrl.newPassword)" translate="FILL_FIELD"></span>
					</div>
					<div class="col-xs-10 col-xs-offset-1">
						<input type="password" name="newPasswordBis" class="form-control" ng-class="{'error-input': generalSettingsCtrl.existPasswordRequiredError(generalSettingsCtrl.newPasswordBis)}" ng-model="generalSettingsCtrl.newPasswordBis" placeholder="{{'CONFIRM_NEW_PASSWORD' | translate }}">
						<span class="purple-text" ng-if="generalSettingsCtrl.existPasswordRequiredError(generalSettingsCtrl.newPasswordBis)" translate="FILL_FIELD"></span>
						<span class="purple-text" ng-if="generalSettingsCtrl.distinctPasswords" translate="PASSWORD_NOT_MATCH"></span>
					</div>
					<div class="text-center">						
						<button class="btn btn-default btn-green" ng-click="generalSettingsCtrl.updatePassword()" ng-disabled="generalSettingsCtrl.savingPassword" translate="CHANGE"></button>
						<span class="purple-text col-xs-12" ng-bind="generalSettingsCtrl.errorMsg"></span>
					</div>
				</div>
			</form>
			<div class="text-center" ng-show="generalSettingsCtrl.savingPassword">
				<img src="/imgs/loading.gif">
			</div>
		</div>
		<div class="modal-footer">
        </div>
	</script>
</div>
