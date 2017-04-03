<?php
use app\assets\desktop\deviser\MakeProfilePublicAsset;

MakeProfilePublicAsset::register($this)

?>
<div class="top-bar-red ng-class:{'top-bar-violet': makeProfilePublicCtrl.errorsRequired}" ng-controller="makeProfilePublicCtrl as makeProfilePublicCtrl" data-spy="affix" data-offset-top="120">
	<div ng-if="makeProfilePublicCtrl.type === 2" ng-cloak>
		<span ng-if="!makeProfilePublicCtrl.errorsRequired" ng-cloak>Your profile is not yet public. First, you need to fill in the HEADER, ABOUT SECTION, AND CREATE ONE WORK.</span>
		<span ng-if="makeProfilePublicCtrl.errorsRequired" ng-cloak>Please fill in the HEADER, ABOUT SECTION & CREATE ONE WORK before making your profile public.</span>
	</div>
	<div ng-if="makeProfilePublicCtrl.type === 3" ng-cloak>
		<span ng-if="!makeProfilePublicCtrl.errorsRequired" ng-cloak>Your profile is not yet public. First, you need to fill in the header & ABOUT SECTION.</span>
		<span ng-if="makeProfilePublicCtrl.errorsRequired" ng-cloak>Your profile is not yet public. First, you need to fill in the HEADER & ABOUT SECTION.</span>
	</div>
	<button class="btn btn-red" ng-click="makeProfilePublicCtrl.active()">Make profile public</button>
</div>