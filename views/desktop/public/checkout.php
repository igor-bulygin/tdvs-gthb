<?php

use app\assets\desktop\cart\GlobalAsset;
use yii\helpers\Json;

/* @var $this yii\web\View */

GlobalAsset::register($this);

$this->title = Yii::t('app/public', 'CHECKOUT');

$this->params['person'] = $person;
$this->registerJs("var person= ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');
?>

<div class="store" ng-controller="checkoutCtrl as checkoutCtrl">
	<div class="hidden-xs hidden-sm cart-top-bar">
		<div class="container">
			<div class="pull-right">
				<a href="/" class="continue-shopping-btn">
					<i class="ion-arrow-left-b"></i>
					<span translate="cart.CONTINUE_SHOPPING"></span>
				</a>
			</div>
		</div>
	</div>
	<div class="container checkout-sidebar no-pad">
		<div ng-show="!checkoutCtrl.saving">
                        <div class="hidden-md hidden-lg col-xs-12 col-sm-12 mt-10">
                                <span class="checkout-title">
                                        <span class="cart-icon-black pull-left"></span>
                                        <span class="shopping-cart-title no-mar" translate="cart.shopping_cart.YOUR_SHOPPING_CART"></span>
                                </span>
                                <cart-summary cart="checkoutCtrl.cart" state="checkoutCtrl.checkout_state" tags="checkoutCtrl.tags"></cart-summary>
																<div class="hidden-md hidden-lg col-xs-12 col-sm-12 cart-shipping-included">
																	<span translate="cart.SHIPPING_PRICES_INCLUDED"></span>
																</div>
                        </div>
			<div class="col-xs-12 col-sm-12 col-md-8 no-pad">
				<personal-info ng-if="checkoutCtrl.cart&&checkoutCtrl.countries"  ng-cloak cart="checkoutCtrl.cart" state="checkoutCtrl.checkout_state" countries="checkoutCtrl.countries"></personal-info>
				<shipping-methods cart="checkoutCtrl.cart" state="checkoutCtrl.checkout_state"></shipping-methods>
				<payment-methods cart="checkoutCtrl.cart" state="checkoutCtrl.checkout_state" countries="checkoutCtrl.countries" saving="checkoutCtrl.saving"></payment-methods>
			</div>
			<div class="hidden-xs hidden-sm col-md-4 no-pad summary-side bordered-left">
				<cart-summary cart="checkoutCtrl.cart" state="checkoutCtrl.checkout_state" tags="checkoutCtrl.tags"></cart-summary>
			</div>
		</div>
		<div class="text-center" ng-if="checkoutCtrl.saving">
			<p><label translate="cart.PAYMENT_IN_PROGRESS"></label></p>
			<i class="fa fa-spinner fa-pulse fa-3x fa-fw mt-20 mb-20"></i>
		</div>
	</div>
</div>
