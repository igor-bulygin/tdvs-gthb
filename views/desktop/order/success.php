<?php
use app\assets\desktop\cart\GlobalAsset;
use yii\helpers\Json;
use app\models\Person;

/* @var $this yii\web\View */

GlobalAsset::register($this);
$this->params['person'] = $person;
$this->params['order_id'] = $order_id;
$this->registerJs("var person= ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');
$this->registerJs("var order_id= ".Json::encode($order_id), yii\web\View::POS_HEAD, 'order-id-var-script');

$this->title = 'Todevise / Your purchase is complete';
?>

<div class="store" ng-controller="orderSuccessCtrl as orderSuccessCtrl">
	<div class="success-header">
		<div class="container">
			<div class="row no-mar">
				<div class="col-md-8">
					<span class="green-title">Congratulations!</span>
					<span class="success-header-message">Your purchase is complete</span>
					<span class="success-header-tagline">A copy of the order receipt has been sent to your mail.</span>
				</div>
				<div class="col-md-2">
					<a href="/">
						<button class="btn btn-red btn-medium pull-right mt-60">Continue shopping</button>
					</a>
				</div>
				<div class="col-md-2">
					<a href="/">
						<button class="btn btn-medium btn-white pull-right mt-60">Download receipt</button>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row no-mar">
			<div class="col-md-3">
				<table class="table table-condensed table-order-success">
					<tr>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td class="table-label">Order id</td>
						<td class="table-item" ng-bind="orderSuccessCtrl.order.id"></td>
					</tr>
					<tr>
						<td class="table-label">Payment method</td>
						<td class="table-item" ng-bind="orderSuccessCtrl.order.payment_info.card.brand + ' **** ' + orderSuccessCtrl.order.payment_info.card.last4"></td>
					</tr>
					<tr>
						<td class="table-label">Phone</td>
						<td class="table-item" ng-bind="orderSuccessCtrl.order.shipping_address.phone_number_prefix + ' ' + orderSuccessCtrl.order.shipping_address.phone_number"></td>
					</tr>
					<tr>
						<td class="table-label">Email</td>
						<td class="table-item" ng-bind="orderSuccessCtrl.person.email"></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="col-md-8 col-md-offset-1">
				<div class="order-success-summary-wrapper">
					<div ng-repeat="pack in orderSuccessCtrl.order.packs" ng-cloak>
						<span class="deviser-name-item summary-white-item" ng-bind="pack.deviser_info.name"></span>
						<span class="pull-right">Shipping price <span ng-bind="pack.shipping_price"></span></span>
						<div class="summary-cart-wrapper summary-white-item" ng-repeat="product in pack.products">
							<div class="img-checkout-wrapper col-md-2">
								<img ng-src="{{product.product_info.photo}}" ng-attr-alt="{{product.product_info.name}}" ng-attr-title="{{product.product_info.name}}">
							</div>
							<div>
								<div>
									<span class="bold"><span ng-bind="product.product_info.name"></span></span>
									<span class="pull-right bold">€&nbsp;<span ng-bind="product.quantity*product.price"></span></span>
								</div>
								<div class="summary-row">
									<span>Ud: <span ng-bind="product.quantity"></span></span><span>&nbsp;·&nbsp;</span>
									<span>Tags: 
										<span ng-repeat="tag in product.tags">
											<span ng-repeat="value in tag.values track by $index">
												<span ng-if="!orderSuccessCtrl.isObject(value)">
													<span ng-if="$index>0">/&nbsp;</span><span ng-bind="(value|capitalize)"></span>
												</span>
											</span>
											<span ng-if="!$last">,</span>
										</span>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="summary-cart-wrapper" ng-cloak>
						<span class="subtotal-amount-wrapper">
							<span class="pull-right bold"><span>Total</span> € <span ng-bind="orderSuccessCtrl.order.subtotal"></span></span>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

