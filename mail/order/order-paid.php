
<?php
/** @var $this \yii\web\View view component instance */
/** @var \app\models\Order $order*/
?>

<div><p>Your purchase is complete:</p></div>

<div><p>Order id: <?= $order->short_id ?></p></div>
<div><p>Name: <?= $order->clientInfoMapping->getFullName() ?></p></div>
<div><p>Payment methods: <?= $order->payment_info['token']['card']['brand'].' **** '.$order->payment_info['token']['card']['last4'] ?></p></div>
<div><p>Phone 1: <?= $order->clientInfoMapping->getPhone1() ?></p></div>
<div><p>Phone 2: <?= $order->clientInfoMapping->getPhone2() ?></p></div>
<div><p>Email: <?= $order->clientInfoMapping->email ?></p></div>
