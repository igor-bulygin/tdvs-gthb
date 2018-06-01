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
		<div class="row">
			<span class="row" translate="person.timeline.START"></span>
			<button  class="btn btn-red btn-add-box" ng-click="timelineCtrl.modalLogin()"><span translate="person.BECOME_MEMBER"></span></button>
			<span class="row" translate="person.IF_MEMBER"></span>
			<span class="row" translate="person.timeline.DISCOVERY_FEED"></span>
			<span class="row" translate="person.timeline.TIMELINE_DESCR"></span>
			<span class="row" translate="person.timeline.BUILD_COMMUNITY"></span>
		</div>
	</div>
</div>