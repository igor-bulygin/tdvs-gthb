<?php
use app\assets\desktop\settings\GlobalAsset;
use app\components\SettingsHeader;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = 'My orders - ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['settings_menu_active_option'] = 'orders';
$this->registerJs('var person = ' .Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

<?php if($person->isPublic()) { ?>
<?= SettingsHeader::widget() ?>
<?php } ?>

<div ng-controller="ordersCtrl as ordersCtrl">
	<div class="col-md-12 store">
		<div class="col-md-1" ng-if="ordersCtrl.isDeviser" ng-cloak>
			<ol class="nya-bs-select col-md-12" ng-model="ordersCtrl.typeFilter" ng-change="ordersCtrl.getOrders()">
				<li nya-bs-option="type in ordersCtrl.enabledTypes">
					<a href="#"><span ng-bind="type.name"></span></a>
				</li>
			</ol>
		</div>
		<div class="col-md-2">
			<div class="radio radio-inline" ng-repeat="state in ordersCtrl.enabledStates">
				<input type="radio" name="userdetails" value="{{state.value}}" ng-model="ordersCtrl.stateFilter" ng-change="ordersCtrl.getOrders()"/>
				<label ng-bind="state.name"></label>
			</div>
		</div>
	</div>
	<div class="col-md-10 col-md-offset-1  store">
		<div ng-switch on="ordersCtrl.typeFilter.value" class="col-md-12" ng-if="!ordersCtrl.loading" ng-cloak>
			<sold-orders ng-switch-when="received" orders="ordersCtrl.orders"></sold-orders>
			<bought-orders ng-switch-when="done" orders="ordersCtrl.orders"></bought-orders>
		</div>
		<div class="text-center" ng-if="ordersCtrl.loading" ng-cloak>
			<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
			<span class="sr-only">Loading...</span>
		</div>
	</div>
</div>
</div>