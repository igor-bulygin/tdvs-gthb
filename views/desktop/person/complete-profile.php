<?php

/* @var app\models\Person $person */

\app\assets\desktop\pub\CreateDeviserAsset::register($this);

$this->title = 'Complete your profile - Todevise';
$this->registerJs("var person = ".\yii\helpers\Json::encode($person), yii\web\View::POS_HEAD, 'complete-profile-var-script');

?>

<div class="create-deviser-account-wrapper">
	<div class="logo">
		<span class="title-create-account">
			<span class="first-title">welcome</span>
			<span class="second-title">to</span>
		</span>
		<a href="#">
			<img src="/imgs/logo.png" data-pin-nopin="true">
		</a>
	</div>

	<div class="invitation-messages">
		<p>We need just a little bit more information about your brand</p>
	</div>

</div>