<?php
use app\components\assets\cropAsset;
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Url;
use app\assets\desktop\deviser\MakeProfilePublicAsset;

MakeProfilePublicAsset::register($this)

?>
<div class="top-bar-red" ng-controller="makeProfilePublicCtrl as makeProfilePublicCtrl">
	<span>Your profile is not yet public. First, you need to fill in the fields below.</span>
	<button class="btn btn-red" ng-click="makeProfilePublicCtrl.active()">Make profile public</button>
</div>