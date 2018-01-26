<?php

use app\assets\desktop\settings\GlobalAsset;
use app\components\SettingsHeader;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = Yii::t('app/public', 'SETTINGS');

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
			<div uib-accordion-group class="panel-default panel-billing" heading="{{ 'settings.general.PERSONAL_INFORMATION' | translate }}" is-open="true" ng-cloak>
				<form name="generalSettingsCtrl.dataForm" class="form-horizontal" ng-show="!generalSettingsCtrl.saving">
					<div class="col-md-12 no-pad">
						<div class="col-md-6 no-pad">
							<div ng-if="generalSettingsCtrl.isDeviser">
								<div>
									<label for="brand_name" translate="settings.general.BRAND"></label>
									<input type="text" name="brand_name" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.brand_name" ng-class="{'error-input': generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.brand_name)}"><!--only devisers -->
									<span class="purple-text" ng-if="generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.brand_name)" translate="settings.FILL_FIELD"></span>
								</div>
							</div>
							<div class="mt-20">
								<div class="col-md-8 no-pad">
									<div class="col-md-12 no-pad">
										<label for="representative_name" translate="settings.general.REPRESENTATIVE_NAME"></label>
									</div>
									<div>
										<div class="col-xs-6 no-pad">
											<input class="form-control"  type="text" name="name" ng-model="generalSettingsCtrl.person.personal_info.name" placeholder="{{ 'global.user.FIRST_NAME' | translate }}" ng-class="{'error-input': generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.name)}">
											<span class="purple-text col-xs-12 no-pad" ng-if="generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.name)" translate="settings.FILL_FIELD"></span>
										</div>
										
										<div class="col-xs-6 responsive-pad-right-0">
											<input class="form-control" type="text" name="last_name" ng-model="generalSettingsCtrl.person.personal_info.last_name" placeholder="{{ 'global.user.LAST_NAME' | translate }}" ng-class="{'error-input': generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.last_name)}">
											<span class="purple-text col-xs-12 responsive-pad-right-0" ng-if="generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.last_name)" translate="settings.FILL_FIELD"></span>
										</div>
										
									</div>
								</div>
								<div class="col-md-4 identifier-pad">
									<label for="vat_id" translate="settings.general.IDENTIFIER"></label>
									<input type="text" name="vat_id" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.vat_id" ng-class="{'error-input': generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.vat_id)}">
									<span class="purple-text" ng-if="generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.vat_id)" translate="settings.FILL_FIELD"></span>
								</div>
							</div>
							
							<div class="blockify mt-20">
								<div class="col-md-6 no-pad">
									<div class="col-md-12 no-pad">
										<label for="phone"><span translate="global.user.PHONE"></span></label>
									</div>
									<div class="col-md-12 no-pad">
										<div class="col-xs-4 no-pad">
											<input type="tel" name="phone_prefix" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.phone_number_prefix" ng-class="{'error-input': generalSettingsCtrl.invalidPrefix }" ng-change="generalSettingsCtrl.setPrefix()">
										</div>
										<div class="col-xs-8 text-right responsive-pad-right-0">
											<input type="tel" name="phone" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.phone_number" ng-class="{'error-input': generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.phone_number)}">
											<span class="purple-text" ng-if="generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.phone_number)" translate="settings.FILL_FIELD"></span>
										</div>
									</div>
								</div>
								<div class="col-md-6 no-pad">
									<div>
										<label for="address"><span translate="global.user.ADDRESS"></span></label>
										<input type="text" name="address" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.address" ng-class="{'error-input': generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.address)}">
										<span class="purple-text" ng-if="generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.address)" translate="settings.FILL_FIELD"></span>
									</div>
								</div>
							</div>
							<div class="blockify mt-20">
								<div class="col-md-6 no-pad-left responsive-pad-right-0">
									<div>
										<label for="city" ng-class="{'col-md-offset-6': !generalSettingsCtrl.isDeviser}"><span translate="global.user.CITY"></span></label>
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
										<span class="purple-text" ng-if="generalSettingsCtrl.existRequiredError(generalSettingsCtrl.city)" translate="settings.FILL_FIELD"></span>
									</div>
								</div>
								<div class="col-md-6 no-pad">
									<div>
										<label for="zip"><span translate="global.user.ZIP"></span></label>
										<input type="text" name="zip" class="form-control" ng-model="generalSettingsCtrl.person.personal_info.zip" ng-class="{'error-input': generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.zip)}">
										<span class="purple-text" ng-if="generalSettingsCtrl.existRequiredError(generalSettingsCtrl.person.personal_info.zip)" translate="settings.FILL_FIELD"></span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 settings-right-side">
							<div class="col-md-12 no-pad">
								<div class="col-xs-6 no-pad">
									<div>
										<label for="password"><span translate="global.user.PASSWORD"></span></label>
										<input type="password" name="password" class="form-control" placeholder="*********" disabled>
									</div>
								</div>
								<div class="col-xs-6">
									<div>
										<a href="#" class="btn btn-change-password" ng-click="generalSettingsCtrl.openModal()"><span translate="settings.general.CHANGE_PASSWORD"></span></a>
									</div>
								</div>
							</div>
							<div class="col-md-12 no-pad blockify mt-20">
								<div class="col-md-6 no-pad">
									<div>
										<label for="weight_measure"><span translate="settings.general.WEIGHT_MEASURE"></span></label>
										<ol name="weightMeasure" class="nya-bs-select btn-group bootstrap-select form-control product-select" ng-model="generalSettingsCtrl.person.settings.weight_measure" ng-class="{'error-input': generalSettingsCtrl.notWeightMeasureSelected}" ng-change="generalSettingsCtrl.notWeightMeasureSelected=false" ng-show="generalSettingsCtrl.weightCharged">
											<li nya-bs-option="weightMeasure in generalSettingsCtrl.weightMeasures">
												<a href="#"><span ng-bind="weightMeasure"></span></a>
											</li>
										</ol>
										<span class="purple-text col-xs-12" ng-if="generalSettingsCtrl.showInvalid && generalSettingsCtrl.notWeightMeasureSelected" translate="settings.general.SELECT_MEASUREMENT"></span>
									</div>
								</div>
								<div class="col-md-6">
								</div>
							</div>
						</div>
					</div>
					<div>
						<div class="col-md-12 mt-40">
							<button class="btn btn-small btn-red auto-center" ng-click="generalSettingsCtrl.update()" translate="global.SAVE"></button>
							<span class="purple-text col-xs-12" ng-if="generalSettingsCtrl.showInvalid" translate="settings.FILL_ALL_FIELDS"></span>
						</div>
						<div class="col-xs-12 text-center" ng-if="generalSettingsCtrl.saved&&!generalSettingsCtrl.dataForm.$dirty">
							<span translate="settings.general.CHANGES_SAVED"><i class="ion-checkmark text-red"></i></span>
						</div>
					</div>
				</form>
				<div class="mt-20 tdvs-loading" ng-show="generalSettingsCtrl.saving">
					<i class="fa fa-spinner fa-pulse fa-3x fa-fw small"></i>
				</div>
			</div>
		</uib-accordion>
	</div>
	<script type="text/ng-template" id="passwordModal">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="generalSettingsCtrl.dismiss()">
			<span class="ion-ios-close" aria-hidden="true"></span>
		</button>
			<h3 class="modal-title" id="modal-title" translate="settings.general.CHANGE_PASSWORD"></h3>
		</div>
		<div class="modal-body personal-info-wrapper">
			<form name="generalSettingsCtrl.passwordForm" class="form-horizontal col-xs-12">
				<div ng-hide="generalSettingsCtrl.savingPassword">
					<div class="col-xs-10 col-xs-offset-1 ">
						<input type="password" name="currentPassword" class="form-control mb-20 " ng-class="{'error-input': generalSettingsCtrl.existPasswordRequiredError(generalSettingsCtrl.currentPassword)}" ng-model="generalSettingsCtrl.currentPassword" placeholder="{{'settings.general.CURRENT_PASSWORD' | translate }}">
						<span class="purple-text" ng-if="generalSettingsCtrl.existPasswordRequiredError(generalSettingsCtrl.currentPassword)" translate="settings.FILL_FIELD"></span>
					</div>
					<div class="col-xs-10 col-xs-offset-1">
						<input type="password" name="newPassword" class="form-control mb-20 " ng-class="{'error-input': generalSettingsCtrl.existPasswordRequiredError(generalSettingsCtrl.newPassword)}" ng-model="generalSettingsCtrl.newPassword" placeholder="{{'settings.general.NEW_PASSWORD' | translate }}">
						<span class="purple-text" ng-if="generalSettingsCtrl.existPasswordRequiredError(generalSettingsCtrl.newPassword)" translate="settings.FILL_FIELD"></span>
					</div>
					<div class="col-xs-10 col-xs-offset-1">
						<input type="password" name="newPasswordBis" class="form-control mb-20 " ng-class="{'error-input': generalSettingsCtrl.existPasswordRequiredError(generalSettingsCtrl.newPasswordBis)}" ng-model="generalSettingsCtrl.newPasswordBis" placeholder="{{'settings.general.CONFIRM_NEW_PASSWORD' | translate }}">
						<span class="purple-text" ng-if="generalSettingsCtrl.existPasswordRequiredError(generalSettingsCtrl.newPasswordBis)" translate="settings.FILL_FIELD"></span>
						<span class="purple-text" ng-if="generalSettingsCtrl.distinctPasswords" translate="settings.general.PASSWORD_NOT_MATCH"></span>
					</div>
					<div class="text-center">
						<button class="btn btn-default btn-red btn-minpad btn-chgPass mt-20" ng-click="generalSettingsCtrl.updatePassword()" ng-disabled="generalSettingsCtrl.savingPassword" translate="global.CHANGE"></button>
						<span class="purple-text col-xs-12" ng-bind="generalSettingsCtrl.errorMsg"></span>
					</div>
				</div>
			</form>
			<div class="mt-20 tdvs-loading" ng-show="generalSettingsCtrl.savingPassword">
				<i class="fa fa-spinner fa-pulse fa-3x fa-fw small"></i>
			</div>
		</div>
		<div class="modal-footer">
		</div>
	</script>

<?php if ($person->isDeviser()) { ?>
	<div class="container">
		<uib-accordion>
			<div uib-accordion-group class="panel-default panel-billing" heading="{{ 'settings.general.TODEVISE_STATS' | translate }}" is-open="true" ng-cloak>
				<p><span translate="{{ 'settings.general.TOTAL_SALES' | translate }}"></span>: <?=$salesNumber?></p>
				<p><span translate="{{ 'settings.general.GROSS_AMOUNT' | translate }}"></span>: <?=$grossAmount.$currency?></p>
				<p><span translate="{{ 'settings.general.NET_AMOUNT' | translate }}"></span>: <?=$netAmount.$currency?></p>
				<p><span translate="{{ 'settings.general.PROFILE_VIEWS' | translate }}"></span>: <?=$profileViews?></p>
			</div>
		</uib-accordion>
	</div>
<?php } ?>


</div>
