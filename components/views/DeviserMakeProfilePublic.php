<?php
use app\components\assets\cropAsset;
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Url;

// use params to share data between views :(
/** @var Person $deviser */
$deviser = $this->params['deviser'];

?>
<div class="top-bar-red">
	<span>Your profile is not yet public</span>
	<button class="btn btn-red">Make profile public</button>
</div>
