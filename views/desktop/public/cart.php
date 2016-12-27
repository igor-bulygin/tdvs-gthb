<?php
use app\assets\desktop\cart\CheckoutAsset;


/* @var $this yii\web\View */

CheckoutAsset::register($this);

$this->title = 'Todevise / Cart';
?>

<div class="store">
	<div class="container">
		<div class="pull-right"><a href="/">Continue shopping</a></div>
		<shopping-cart></shopping-cart>

	</div>
</div>