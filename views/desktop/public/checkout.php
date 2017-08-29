<?php

use app\assets\desktop\cart\GlobalAsset;
use yii\helpers\Json;

/* @var $this yii\web\View */

GlobalAsset::register($this);

$this->title = Yii::t('app/public', 'Checkout - Todevise');

$this->params['person'] = $person;
$this->registerJs("var person= ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');
?>

<div class="store" ng-controller="checkoutCtrl as checkoutCtrl">
	<div class="cart-top-bar">
		<div class="container">
			<div class="pull-right">
				<a href="/" class="continue-shopping-btn">
					<i class="ion-arrow-left-b"></i>
					<span translate="CONTINUE_SHOPPING"></span>
				</a>
			</div>
		</div>
	</div>
	<div class="container checkout-sidebar no-pad">
		<div class="col-md-8 no-pad">
			<personal-info cart="checkoutCtrl.cart" state="checkoutCtrl.checkout_state" countries="checkoutCtrl.countries"></personal-info>
			<shipping-methods cart="checkoutCtrl.cart" state="checkoutCtrl.checkout_state"></shipping-methods>
			<payment-methods cart="checkoutCtrl.cart" state="checkoutCtrl.checkout_state" countries="checkoutCtrl.countries"></payment-methods>
		</div>
		<div class="col-md-4 no-pad summary-side bordered-left">
			<cart-summary cart="checkoutCtrl.cart" state="checkoutCtrl.checkout_state"></cart-summary>
		</div>
	</div>
</div>
