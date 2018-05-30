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

<div class="store" ng-controller="timelineCtrl as timelineCtrl">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<span >Tipos</span>
			</div>
			<div class="col-md-5">
				<div ng-if="timelineCtrl.timeline.length > 0" ng-cloak>
					<div class="col-xs-12" ng-repeat="timeline in timelineCtrl.timeline" ng-cloak>
						<div class="menu-category list-group" >
							<div class="row">
								<span class="col-xs-6" ng-bind="timeline.person.name"></span>
								<span class="col-xs-6" ng-bind="timeline.action_name"></span>
								<span class="col-xs-6" am-time-ago="timelineCtrl.parseDate(timeline.date.sec*1000)"></span>
							</div>
							<div class="row">
								<img class="col-xs-12 grid-image" ng-src="{{timeline.photo}}">
							</div>
							<div class="row text-right">
								<span ng-bind="timeline.loveds"></span>
								<span class="icons-hover heart-icon heart-red-icon"></span>
							</div>
							<div class="row">
								<span ng-bind-html="timeline.title"></span>
							</div>
							<div class="row">
								<span ng-bind-html="timeline.description"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>