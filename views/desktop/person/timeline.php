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
			<div class="col-xs-12 col-md-3">
				<span  ng-click="timelineCtrl.resetFilter()" 
					class="ng-class:{'active-menu': !timelineCtrl.selectedPersonType}" translate="person.ALL"></span>
				<div ng-repeat="personType in timelineCtrl.personTypes">
					<span translate="{{personType.name}}" ng-click="timelineCtrl.selectedPersonType = personType.value" 
					class="ng-class:{'active-menu': timelineCtrl.selectedPersonType === personType}"></span>
				</div>
			</div>
			<div class="col-xs-12 col-md-8 col-lg-7">
				<div class="timeline-wrapper">
					<div ng-if="timelineCtrl.timeline.length <1" ng-cloak>
					</div>
					<div infinite-scroll="timelineCtrl.addMoreItems()" infinite-scroll-distance="timelineCtrl.show_items-1">
						<div ng-if="timelineCtrl.timeline.length > 0" ng-cloak>
							<div class="col-xs-12" ng-repeat="timeline in timelineCtrl.timeline | filter:{ person: {person_type:timelineCtrl.selectedPersonType} }" ng-cloak>
								<div class="menu-category list-group" >
									<div class="row">
										<img class="col-xs-3 avatar-logued-user" ng-src="{{ timelineCtrl.parseImage(timeline.person.url_avatar)}}">
										<span class="col-xs-3" ng-bind="timeline.person.name"></span>
										<span class="col-xs-3" ng-bind="timeline.action_name"></span>
										<span class="col-xs-3" am-time-ago="timeline.date | amUtc"></span>
									</div>
									<div class="row">
										<img class="col-xs-12 grid-image" ng-src="{{timeline.photo}}">
									</div>
									<div class="row text-right">
										<span ng-bind="timeline.loveds"></span>
										<span ng-if="timeline.isLoved" class="icons-hover heart-icon heart-red-icon" ng-click="timelineCtrl.unLoveTimeline(timeline)" ng-cloak></span>
										<span ng-if="!timeline.isLoved" class="icons-hover heart-icon heart-black-icon" ng-click="timelineCtrl.loveTimeline(timeline)" ng-cloak></span>
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
	</div>
</div>