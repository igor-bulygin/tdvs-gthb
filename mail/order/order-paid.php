
<?php
/** @var $this \yii\web\View view component instance */
/** @var \app\models\Order $order*/
?>

<div><p>Your purchase is complete:</p></div>

<div><p>Order id: <?= $order->short_id ?></p></div>
<div><p>Name: <?= $order->personInfoMapping->getFullName() ?></p></div>
<div><p>Payment methods: <?= $order->getPaymentMethod() ?></p></div>
<div><p>Phone 1: <?= $order->personInfoMapping->getPhone1() ?></p></div>
<div><p>Phone 2: <?= $order->personInfoMapping->getPhone2() ?></p></div>
<div><p>Email: <?= $order->personInfoMapping->email ?></p></div>
