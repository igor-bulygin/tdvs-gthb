<?php
use app\assets\desktop\cart\CheckoutAsset;


/* @var $this yii\web\View */

CheckoutAsset::register($this);

$this->title = 'Todevise / Cart';
?>

<div class="store" ng-controller="checkoutCtrl as checkoutCtrl">
	<div class="container">
		<div class="pull-right"><a href="/">Continue shopping</a></div>
		<div class="col-md-9">
			<shopping-cart state="checkoutCtrl.cart_state" cart="checkoutCtrl.cart" devisers="checkoutCtrl.devisers" ng-if="checkoutCtrl.cart_state.state===1"></shopping-cart>
			<personal-info state="checkoutCtrl.cart_state" cart="checkoutCtrl.cart" ng-if="checkoutCtrl.cart_state.state===2"></personal-info>
		</div>
		<div class="col-md-3">
			<cart-summary state="checkoutCtrl.cart_state" cart="checkoutCtrl.cart" devisers="checkoutCtrl.devisers"></cart-summary>
		</div>
	</div>
</div>