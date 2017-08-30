<?php

use app\assets\desktop\cart\GlobalAsset;

/* @var $this yii\web\View */

GlobalAsset::register($this);

$this->title = Yii::t('app/public', 'Cart');
?>

<div class="store" ng-controller="cartOverviewCtrl as cartOverviewCtrl">
		<div class="cart-top-bar">
			<div class="container">
				<div class="pull-right">
					<a class="continue-shopping-btn" href="/">
						<i class="ion-arrow-left-b"></i>
						<span translate="CONTINUE_SHOPPING"></span>
					</a>
				</div>
			</div>
		</div>
		<div class="container checkout-sidebar no-pad">
			<div class="col-md-8 no-pad">
				<shopping-cart cart="cartOverviewCtrl.cart"></shopping-cart>
			</div>
			<div class="col-md-4 no-pad summary-side bordered-left">
				<cart-summary cart="cartOverviewCtrl.cart"></cart-summary>
			</div>
		</div>
</div>