<?php
use app\assets\desktop\cart\GlobalAsset;
use yii\helpers\Json;
use app\models\Person;

/* @var $this yii\web\View */

GlobalAsset::register($this);

$this->title = 'Todevise / Checkout';
$this->params['person'] = $person;
$this->registerJs("var person= ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');
?>

<div class="store" ng-controller="checkoutCtrl as checkoutCtrl">
	<div class="cart-top-bar">
		<div class="container">
			<div class="pull-right">
				<a href="/" class="continue-shopping-btn">
					<i class="ion-arrow-left-b"></i>
					Continue shopping
				</a>
			</div>
		</div>
	</div>
	<div class="container checkout-sidebar no-pad">
		<div class="col-md-8 no-pad">
			<personal-info cart="checkoutCtrl.cart"></personal-info>
		</div>
		<div class="col-md-4 no-pad summary-side bordered-left">
			<cart-summary cart="checkoutCtrl.cart"></cart-summary>
		</div>
	</div>
</div>
