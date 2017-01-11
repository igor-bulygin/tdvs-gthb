<?php
use app\assets\desktop\cart\CheckoutAsset;


/* @var $this yii\web\View */

CheckoutAsset::register($this);

$this->title = 'Todevise / Cart';
?>

<div class="store" ng-controller="checkoutCtrl as checkoutCtrl">
		<div class="cart-top-bar">
			<div class="container">
				<div class="pull-right">
					<a class="continue-shopping-btn" href="/">
						<i class="ion-arrow-left-b"></i>
						Continue shopping
					</a>
				</div>
			</div>
		</div>
		<div class="container checkout-sidebar no-pad">
			<div class="col-md-8 no-pad">
				<shopping-cart state="checkoutCtrl.cart_state" cart="checkoutCtrl.cart" devisers="checkoutCtrl.devisers" ng-if="checkoutCtrl.cart_state.state===1"></shopping-cart>
				<personal-info state="checkoutCtrl.cart_state" cart="checkoutCtrl.cart" ng-if="checkoutCtrl.cart_state.state===2"></personal-info>
			</div>
			<div class="col-md-4 no-pad bordered-left">
				<cart-summary state="checkoutCtrl.cart_state" cart="checkoutCtrl.cart" devisers="checkoutCtrl.devisers"></cart-summary>
			</div>
		</div>
</div>