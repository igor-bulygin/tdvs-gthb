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

<?php if($person->isCompletedProfile()) { ?>
<?= SettingsHeader::widget() ?>
<?php } ?>

<div ng-controller="ordersCtrl as ordersCtrl">
	<div class="col-md-12 store" style="padding: 20px;">
		<h4 class="col-md-7 col-md-offset-5" translate="ORDERS"></h4>
		<div class="col-md-1" ng-if="ordersCtrl.isDeviser" ng-cloak>
			<ol class="nya-bs-select col-md-12" ng-model="ordersCtrl.typeFilter" ng-change="ordersCtrl.getOrders()" ng-if="ordersCtrl.isDeviser" ng-cloak>
				<li nya-bs-option="type in ordersCtrl.enabledTypes">
					<a href="#">{{ type.name | translate }}</a>
				</li>
			</ol>
		</div>
		<div class="btn-group col-md-11 inline">
			<label class="col-md-1" ng-repeat="state in ordersCtrl.enabledStates" class="col-md-1">
				<input type="radio" name="stateFilter" ng-model="ordersCtrl.stateFilter" ng-value="state.value" ng-change="ordersCtrl.getOrders()" />
				<span ng-cloak>{{state.name | translate }}</span>
			</label>
		</div>
		<div class="col-md-12 text-center" ng-if="!ordersCtrl.loading && !ordersCtrl.orders.length>0" ng-cloak>
			<span translate="NO_ORDERS_FOUNDED"></span>
		</div>
	</div>	
	<div class="col-md-10 col-md-offset-1 store text-center" ng-if="ordersCtrl.loading" style="padding: 20px;" ng-cloak>
		<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
		<span class="sr-only" translate="LOADING"></span>
	</div>
	<div ng-switch on="ordersCtrl.typeFilter.value" ng-if="!ordersCtrl.loading && ordersCtrl.orders.length>0" ng-cloak>
		<sold-orders ng-switch-when="received" orders="ordersCtrl.orders" ordersTotalPrice="ordersCtrl.ordersTotalPrice"></sold-orders>
		<bought-orders ng-switch-when="done" orders="ordersCtrl.orders" ordersTotalPrice="ordersCtrl.ordersTotalPrice"></bought-orders>
	</div>
	
</div>