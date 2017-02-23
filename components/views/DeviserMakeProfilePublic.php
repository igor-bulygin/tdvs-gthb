<?php
use app\components\assets\cropAsset;
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Url;
use app\assets\desktop\deviser\MakeProfilePublicAsset;

MakeProfilePublicAsset::register($this)

?>
<div class="top-bar-red ng-class:{'top-bar-violet': makeProfilePublicCtrl.errorsRequired}" ng-controller="makeProfilePublicCtrl as makeProfilePublicCtrl">
	<span ng-if="!makeProfilePublicCtrl.errorsRequired" ng-cloak>Your profile is not yet public. First, you need to fill in the fields below.</span>
	<span ng-if="makeProfilePublicCtrl.errorsRequired" ng-cloak>Please complete all the required fields before making your profile public.</span>
	<button class="btn btn-red" ng-click="makeProfilePublicCtrl.active()">Make profile public</button>
</div>