<?php

use app\assets\desktop\cart\GlobalAsset;

/* @var $this yii\web\View */

GlobalAsset::register($this);

$this->title = Yii::t('app/public', 'CART');
?>

<div class="store" ng-controller="cartOverviewCtrl as cartOverviewCtrl">
		<div class="hidden-xs hidden-sm cart-top-bar">
			<div class="container">
				<div class="pull-right">
					<a class="continue-shopping-btn" href="/">
						<i class="ion-arrow-left-b"></i>
						<span translate="cart.CONTINUE_SHOPPING"></span>
					</a>
				</div>
			</div>
		</div>
		<div class="container checkout-sidebar no-pad">
			<div class="hidden-md hidden-lg col-xs-12 col-sm-12 mt-10">
				<span class="checkout-title">
					<span class="cart-icon-black pull-left"></span>
					<span class="shopping-cart-title no-mar" translate="cart.shopping_cart.YOUR_SHOPPING_CART"></span>
				</span>
				<cart-summary cart="cartOverviewCtrl.cart" tags="cartOverviewCtrl.tags"></cart-summary>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-8 no-pad">
				<shopping-cart cart="cartOverviewCtrl.cart" tags="cartOverviewCtrl.tags"></shopping-cart>
			</div>
			<div class="hidden-xs hidden-sm col-md-4 no-pad summary-side bordered-left">
				<cart-summary cart="cartOverviewCtrl.cart" tags="cartOverviewCtrl.tags"></cart-summary>
			</div>
			<div class="hidden-md hidden-lg col-xs-12 col-sm-12">
				<div class="summary-cart-wrapper mb-20 mt-20" ng-if="!summaryCtrl.state">
					<a href="/checkout">
						<button class="btn btn-red col-xs-12" translate="cart.summary.PROCEED"></button>
					</a>
				</div>
				<div class="text-center mb-40"><a href="/" class="red-link-btn"><span translate="cart.CONTINUE_SHOPPING"></span></a></div>
			</div>
		</div>
</div>
