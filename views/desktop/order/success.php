<?php
use app\assets\desktop\cart\OrderAsset;


/* @var $this yii\web\View */

OrderAsset::register($this);

$this->title = 'Todevise / Your purchase is complete';
?>

<div class="store" ng-controller="orderSuccessCtrl as orderSuccessCtrl">
	<div class="row">
		<div class="col-md-8">
			<h3>Congratulations!</h3>
			<h1>Your purchase is complete</h1>
			<p>A copy of the order receipt has been sent to your mail.</p>
		</div>
		<div class="col-md-4">
			<button class="btn btn-default btn-green">Continue shopping</button>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2">
			<table class="table table-condensed">
				<tr>
					<td>Order id</td>
					<td ng-bind="orderSuccessCtrl.cart.id"></td>
				</tr>
				<tr>
					<td>Payment method</td>
					<td></td>
				</tr>
				<tr>
					<td>Phone</td>
					<td ng-bind="orderSuccessCtrl.cart.client_info.phone1.prefix + ' ' + orderSuccessCtrl.cart.client_info.phone1.number"></td>
				</tr>
				<tr>
					<td>Email</td>
					<td ng-bind="orderSuccessCtrl.cart.client_info.email"></td>
				</tr>
			</table>
		</div>
		<div class="col-md-6 col-md-offset-2">
			<cart-summary state="orderSuccessCtrl.state" cart="orderSuccessCtrl.cart" devisers="orderSuccessCtrl.devisers"></cart-summary>
		</div>
	</div>
</div>

