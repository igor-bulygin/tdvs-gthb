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
			<shopping-cart cart="checkoutCtrl.cart" devisers="checkoutCtrl.devisers"></shopping-cart>
		</div>
		<div class="col-md-3">
			<cart-summary cart="checkoutCtrl.cart" devisers="checkoutCtrl.devisers"></cart-summary>
		</div>
	</div>
</div>