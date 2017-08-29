
<?php
/** @var $this \yii\web\View view component instance */
/** @var \app\models\Order $order*/
$person = $order->getPerson();
$shippingAddress = $order->getShippingAddress();
$billingAddress = $order->getBillingAddress();
?>

<div><p>Your purchase is complete:</p></div>

<div><p>Order id: <?= $order->short_id ?></p></div>
<div><p>Name: <?= $person->getName() ?></p></div>
<div><p>Email: <?= $person->getEmail() ?></p></div>
<div><p>Phone: <?= $shippingAddress->getPhone() ?></p></div>
<div><p>Payment method: <?= $order->getPaymentMethod() ?></p></div>
