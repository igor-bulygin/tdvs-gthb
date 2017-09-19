<?php

use app\assets\desktop\settings\GlobalAsset;
use app\components\SettingsHeader;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = Yii::t('app/public', 'ORDERS');

$this->params['person'] = $person;
$this->params['settings_menu_active_option'] = 'orders';
$this->registerJs('var person = ' .Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

<?php if($person->isCompletedProfile()) { ?>
<?= SettingsHeader::widget() ?>
<?php } ?>

<div ng-controller="ordersCtrl as ordersCtrl">
	<div class="col-md-12 store" style="padding: 20px;">
		<h4 class="col-md-7 col-md-offset-5"><span translate="settings.orders.ORDERS"></span></h4>
		<div class="col-md-4" ng-if="ordersCtrl.isDeviser" ng-cloak>
			<ol class="nya-bs-select btn-group bootstrap-select form-control product-select col-md-12" ng-model="ordersCtrl.typeFilter" ng-change="ordersCtrl.getOrders()" ng-if="ordersCtrl.isDeviser" ng-cloak>
				<li nya-bs-option="type in ordersCtrl.enabledTypes">
					<a href="#"><span translate="{{type.name}}"></span></a>
				</li>
			</ol>
		</div>
		<div class="btn-group col-md-8 inline">
			<label class="col-md-1" ng-repeat="state in ordersCtrl.enabledStates" class="col-md-1">
				<input type="radio" name="stateFilter" ng-model="ordersCtrl.stateFilter" ng-value="state.value" ng-change="ordersCtrl.getOrders()" />
				<span translate="{{state.name}}"></span>
			</label>
		</div>
		<div class="col-md-12 text-center" ng-if="!ordersCtrl.loading && !ordersCtrl.orders.length>0" ng-cloak>
			<span translate="settings.orders.NO_ORDERS_FOUND"></span>
		</div>
	</div>	
	<div class="col-md-10 col-md-offset-1 store text-center" ng-if="ordersCtrl.loading" style="padding: 20px;" ng-cloak>
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
		<span class="sr-only" translate="global.LOADING"></span>
	</div>
	<div ng-switch on="ordersCtrl.typeFilter.value" ng-if="!ordersCtrl.loading && ordersCtrl.orders.length>0" ng-cloak>
		<sold-orders ng-switch-when="received" orders="ordersCtrl.orders" ordersTotalPrice="ordersCtrl.ordersTotalPrice" tags="ordersCtrl.tags"></sold-orders>
		<bought-orders ng-switch-when="done" orders="ordersCtrl.orders" ordersTotalPrice="ordersCtrl.ordersTotalPrice" tags="ordersCtrl.tags"></bought-orders>
	</div>
	
</div>