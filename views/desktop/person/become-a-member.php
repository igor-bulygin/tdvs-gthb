<?php

use app\assets\desktop\deviser\TimelineAsset;
use app\models\Category;
use app\models\Person;
use app\models\Product;

TimelineAsset::register($this);

/** @var Person $person */
/** @var Product[] $products */
/** @var Category $category */
/** @var Category $selectedCategory */

$this->title = Yii::t('app/public','TIMELINE');
Yii::$app->opengraph->title = $this->title;

?>

<div ng-controller="timelineCtrl as timelineCtrl">
	<div class="container">
		<div class="row timeline-login col-xs-10 col-xs-offset-1 col-md-6 col-md-offset-3">
			<span class="row login-start" translate="person.timeline.START"></span>
			<button  class="btn btn-red btn-auto" ng-click="timelineCtrl.modalLogin()"><span translate="person.BECOME_MEMBER"></span></button>
			<span class="row login-signin" translate="person.IF_MEMBER"></span>
			<span class="row login-discovery-feed" translate="person.timeline.DISCOVERY_FEED"></span>
			<span class="row login-descr" translate="person.timeline.TIMELINE_DESCR"></span>
			<span class="row login-descr" translate="person.timeline.BUILD_COMMUNITY"></span>
			<img src="imgs/timeline-empty.jpg" class="img-responsive">
		</div>
	</div>
</div>