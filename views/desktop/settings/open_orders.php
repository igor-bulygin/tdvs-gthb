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
	<div class="container">
		<div class="mb-20 col-md-12">
			<div class="col-md-6">
				<span class="col-md-5">State filter</span>
				<ol class="nya-bs-select col-md-10" ng-model="ordersCtrl.stateFilter" ng-change="ordersCtrl.getOrders()">
					<li nya-bs-option="state in ordersCtrl.enabledStates">
						<a href="#"><span ng-bind="state.name"></span></a>
					</li>
				</ol>
			</div>
			<div class="col-md-6">
				<span class="col-md-5">Type filter</span>
				<ol class="nya-bs-select col-md-10" ng-model="ordersCtrl.typeFilter" ng-change="ordersCtrl.getOrders()">
					<li nya-bs-option="type in ordersCtrl.enabledTypes">
						<a href="#"><span ng-bind="type.name"></span></a>
					</li>
				</ol>
			</div>
		</div>
		<uib-accordion>
			<div uib-accordion-group ng-cloak ng-repeat="order in ordersCtrl.orders">
				<uib-accordion-heading>
					<span class="col-md-4 panel">
						<span class="col-md-12">INVOICE Nº</span>
						<span ng-bind="order.id"></span>
					</span>
					<span class="col-md-4 panel">
						<span class="col-md-12">ORDER DATE</span>
						<span>{{order.order_date | date:'dd/MM/yy'}}</span>
					</span>
					<span class="col-md-4 panel">
						<span class="col-md-12">CLIENT NAME</span>
						<span ng-bind="order.billing_address.name + ' ' + order.billing_address.last_name"></span>
					</span>
				</uib-accordion-heading>
				<div class="col-md-6">
					<h4>Info</h4>
					<div class="col-md-6">
						<p>CLIENT</p>
						<p ng-bind="order.billing_address.name + ' ' + order.billing_address.last_name"></p>
						<p ng-bind="order.billing_address.address"></p>
						<p ng-bind="order.billing_address.city + ', ' + order.billing_address.country"></p>
					</div>
					<div class="col-md-6">
						<div class="col-md-12">
							<span>YOU RECEIVE</span>
							<span ng-bind="order.totalPrice"></span>
						</div>
						<div class="col-md-12">
							<span>TODEVISE COMMISSION</span>
							<span ng-bind="order.commission"></span>
						</div>
						<!-- TODO affiliates 
						<div class="col-md-12">
							<span>AFFILIATES FOR ALL</span>
							<span ng-bind="order.affiliates"></span>
						</div> -->
						<div class="col-md-12">
							<span>SHIPPING</span>
							<span ng-bind="order.totalShippingPrice"></span>
						</div>
					</div>
					<div class="col-md-12 text-right">
						<div class="col-md-12">
							<span>TOTAL</span>
							<span ng-bind="order.total"></span>
						</div>
					</div>
					<div ng-repeat="pack in order.packs">
						<div class="col-md-4" ng-repeat="product in pack.products">
							<img class="col-md-6" ng-src="{{product.product_info.photo}}">
							<div class="col-md-6">
								<label class="col-md-12" ng-bind="product.product_info.name"></label>
								<span class="col-md-10">Quantity: </span><span class="col-md-2" ng-bind="product.quantity"></span>
								<span class="col-md-10">Price: </span><span class="col-md-2" ng-bind="product.price"></span>
								<span class="col-md-10">Size: </span><span class="col-md-2" ng-bind="product.options.size"></span>
								<!--<div class="col-md-12" ng-repeat="color in product.product_info.options.731ct">
									<span class="col-md-12" ng-bind="color"></span>
								</div>-->
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div ng-repeat="pack in order.packs">
						<div ng-switch on="pack.pack_state">
							<div ng-switch-when="aware">
								<div class="col-md-12">
									<div class="col-md-12" >
										<div class="col-md-5 col-md-offset-1">
											<label>I'M AWARE</label>
										</div>
										<div class="col-md-3 col-md-offset-2">
											<span>I'M PREPARING IT</span>
										</div>
									</div>
									<p>When you see the order, please click the button below to inform us</p>
								</div>
								<button class="btn btn-green" ng-click="ordersCtrl.changePackState(order,pack)" ng-enabled="pack.pack_state==='aware'">READY TO CREATE</button>
							</div>
							<div ng-switch-when="preparing">
								<form name="ordersCtrl.shippingForm" class="form-horizontal" >
									<div class="form-group col-md-6">
										<label for="shippingCompany" class="col-md-12">Shipping company</label>
										<div class="col-md-12">
											<input type="text" name="shippingCompany" class="form-control">
										</div>
										<label for="eta" class="col-md-12">ETA <span>OPTIONAL</span></label>
										<div class="col-md-12">
											<input type="text" name="eta" class="form-control" >
										</div>
									</div>
									<div class="form-group col-md-6">
										<label for="trackingNumber" class="col-md-12">Tracking number</label>
										<div class="col-md-12">
											<input type="text" name="trackingNumber" class="form-control" >
										</div>
										<label for="trackLink" class="col-md-12">Link to track package <span>OPTIONAL</span></label>
										<div class="col-md-12">
											<input type="text" name="trackLink" class="form-control">
										</div>
									</div>
									<span class="form-group col-md-12">
										When you click this button, the order will be moved to PAST ORDERS
									</span>
									<div class="form-group col-md-12">
										<button class="btn btn-green" ng-click="ordersCtrl.changeOrderState(order)" ng-enabled="order.state==='preparing'&& ordersCtrl.shippingForm.$valid">PACKAGE WAS SHIPPED</button>
									</div>

								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</uib-accordion>
	</div>
</div>