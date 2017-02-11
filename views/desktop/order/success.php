<?php
use app\assets\desktop\cart\OrderAsset;


/* @var $this yii\web\View */

OrderAsset::register($this);

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
				<div class="col-md-4">
					<button class="btn btn-default btn-green pull-right mt-60">Continue shopping</button>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row no-mar">
			<div class="col-md-2">
				<table class="table table-condensed">
					<tr>
						<td>Order id</td>
						<td ng-bind="orderSuccessCtrl.order.id"></td>
					</tr>
					<tr>
						<td>Payment method</td>
						<td ng-bind="orderSuccessCtrl.order.payment_info.card.brand + ' **** ' + orderSuccessCtrl.order.payment_info.card.last4"></td>
					</tr>
					<tr>
						<td>Phone</td>
						<td ng-bind="orderSuccessCtrl.order.client_info.phone1.prefix + ' ' + orderSuccessCtrl.order.client_info.phone1.number"></td>
					</tr>
					<tr>
						<td>Email</td>
						<td ng-bind="orderSuccessCtrl.order.client_info.email"></td>
					</tr>
				</table>
			</div>
			<div class="col-md-8 col-md-offset-2">
				<cart-summary state="orderSuccessCtrl.state" cart="orderSuccessCtrl.order" devisers="orderSuccessCtrl.devisers"></cart-summary>
			</div>
		</div>
	</div>
</div>

