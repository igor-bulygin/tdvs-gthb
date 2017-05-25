<?php
use app\assets\desktop\settings\ShippingAsset;
use app\components\SettingsHeader;
use app\models\Person;
use yii\helpers\Json;
use yii\helpers\Url;

ShippingAsset::register($this);

/** @var Person $person */

$this->title = 'Shipping - ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['settings_menu_active_option'] = 'shipping';
$this->registerJs('var person = ' .Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

<?= SettingsHeader::widget() ?>

<div ng-controller="shippingSettingsCtrl as shippingSettingsCtrl" class="personal-info-wrapper bank-settings-wrapper">
	<div class="container">
		<div ng-repeat="setting in shippingSettingsCtrl.person.shipping_settings" ng-cloak>
			<uib-accordion>
				<div uib-accordion-group class="panel-default panel-billing" ng-cloak is-open="shippingSettingsCtrl.country_helper[$index]['status']" is-disabled="true">
					<div uib-accordion-heading>
						<span ng-click="shippingSettingsCtrl.toggleStatus($index)" ng-bind="shippingSettingsCtrl.country_helper[$index].country_name"></span>
						<span ng-click="shippingSettingsCtrl.toggleStatus($index)" ng-if="!shippingSettingsCtrl.country_helper[$index]['status']">
							<span class="glyphicon glyphicon-edit pull-right" ng-if="setting.prices && setting.prices.length > 0" ng-cloak></span>
							<button class="btn btn-default btn-green pull-right" ng-if="!setting.prices || setting.prices.length <= 0" ng-cloak>Add shipping prices</button>
						</span>
					</div>
					<shipping-types setting="setting"></shipping-types>
					<hr>
					<shipping-weights-prices setting="setting" weights="shippingSettingsCtrl.metrics.weights"></shipping-weights-prices>
					<hr>
					<shipping-observations setting="setting" languages="shippingSettingsCtrl.languages"></shipping-observations>
					<div class="row text-center">
						<button class="btn btn-default btn-green">Save</button>
						<button class="btn btn-default">Cancel</button>
					</div>
				</div>
			</uib-accordion>
		</div>
	</div>
	<div><pre>{{shippingSettingsCtrl.person.shipping_settings | json}}</pre></div>
</div>