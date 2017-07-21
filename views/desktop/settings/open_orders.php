<?php
use app\assets\desktop\settings\GlobalAsset;
use app\components\SettingsHeader;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = 'My orders - Open orders - ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['settings_menu_active_option'] = 'orders';
$this->registerJs('var person = ' .Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

<?php if($person->isPublic()) { ?>
<?= SettingsHeader::widget() ?>
<?php } ?>

<div ng-controller="openOrdersCtrl as openOrdersCtrl">
	<div class="container">
		<uib-accordion>
			<div uib-accordion-group ng-cloak ng-repeat="order in openOrdersCtrl.orders">
				<uib-accordion-heading>
					<span class="col-md-4 panel">
						<span class="col-md-12">INVOICE Nº</span>
						<span ng-bind="order.id"></span>
					</span>
					<span class="col-md-4 panel">
						<span class="col-md-12">ORDER DATE</span>
						<span>{{order.date | date}}</span>
					</span>
					<span class="col-md-4 panel">
						<span class="col-md-12">CLIENT NAME</span>
						<span ng-bind="order.person_info.first_name + ' ' + order.person_info.last_name"></span>
					</span>
				</uib-accordion-heading>
				<div class="col-md-6">
					<h4>Info</h4>
					<div class="col-md-6">
						<p>CLIENT</p>
						<p ng-bind="order.person_info.first_name + ' ' + order.person_info.last_name"></p>
						<p ng-bind="order.person_info.address"></p>
						<p ng-bind="order.person_info.city + ', ' + order.person_info.country"></p>						
					</div>
					<div class="col-md-6">
						<div class="col-md-12">
							<span>YOU RECEIVE</span>
							<span ng-bind="order.subtotal"></span>
						</div>
						<div class="col-md-12">
							<span>TODEVISE COMMISSION</span>
							<span ng-bind="order.commission"></span>
						</div>
						<div class="col-md-12">
							<span>AFFILIATES FOR ALL</span>
							<span ng-bind="order.affiliates"></span>
						</div>
						<div class="col-md-12">
							<span>SHIPPING</span>
							<span ng-bind="order.shipping"></span>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="col-md-12">
						<div class="col-md-12">
							<div class="btn-group">
								<label class="btn" ng-model="openOrdersCtrl.radioModel" uib-btn-radio="'aware'" uncheckable>I'M AWARE</label>
								<label class="btn" ng-model="openOrdersCtrl.radioModel" uib-btn-radio="'preparing'" uncheckable>I'M PREPARING IT</label>
							</div>
						</div>
						<p>When you see the order, please click the button below to inform us</p>
					</div>
					<button class="btn btn-green" ng-click="openOrdersCtrlchangeDeviserState()" ng-enabled="openOrdersCtrl.deviser.state==='aware'">READY TO CREATE</button>
				</div>
			</div>
		</uib-accordion>
	</div>
</div>