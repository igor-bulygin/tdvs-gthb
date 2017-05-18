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
		<div ng-if="shippingSettingsCtrl.person.shipping_settings.length == 0" ng-cloak>
			<p class="text-center">You have no zones yet.</p>
		</div>
		<div ng-if="shippingSettingsCtrl.person.shipping_settings.length > 0" ng-repeat="zone in shippingSettingsCtrl.person.shipping_settings" ng-cloak>
			<uib-accordion>
				<div uib-accordion-group class="panel-default panel-billing" heading="Zone {{$index+1}}" ng-cloak is-open="shippingSettingsCtrl.status[$index]" is-disabled="true">
					<div uib-accordion-heading>
						<span ng-click="shippingSettingsCtrl.toggleStatus($index)">Zone {{$index+1}}</span>
						<span class="glyphicon glyphicon-remove pull-right" style="cursor:pointer;" ng-click="shippingSettingsCtrl.deleteZone($index)"></span>
						<span class="glyphicon glyphicon-edit pull-right"></span>
					</div>
					<shipping-zones zone="zone"></shipping-zones>
					<hr>
					<shipping-weights-prices zone="zone" weights="shippingSettingsCtrl.metrics.weights"></shipping-weights-prices>
					<hr>
					<shipping-observations zone="zone" languages="shippingSettingsCtrl.languages"></shipping-observations>
					<div class="row text-center">
						<button class="btn btn-default btn-green">Save</button>
						<button class="btn btn-default">Cancel</button>
					</div>
				</div>
			</uib-accordion>
		</div>
		<div class="text-center">
			<button class="btn btn-default btn-green" ng-click="shippingSettingsCtrl.addZone()">Create zone</button>
		</div>
	</div>
</div>