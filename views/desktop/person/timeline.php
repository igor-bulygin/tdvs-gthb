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
			<div class="col-xs-12 col-md-3"><nav class="menu-store"><ul>
				<li>
					<a style="cursor:pointer;" ng-class="{'active': !timelineCtrl.selectedPersonType}" ng-click="timelineCtrl.resetFilter()" translate="person.ALL"></a>
				</li>
				<li ng-repeat="personType in timelineCtrl.personTypes">
					<a style="cursor:pointer;" translate="{{personType.name}}" ng-class="{'active': timelineCtrl.selectedPersonType === personType.value }" ng-click="timelineCtrl.selectedPersonType = personType.value"></a>
				</li>
			</ul></nav></div>
			<div class="col-xs-12 col-md-8 col-lg-7">
				<div class="timeline-wrapper">
					<div ng-if="timelineCtrl.timeline.length <1" ng-cloak>
					</div>
					<div infinite-scroll="timelineCtrl.addMoreItems()" infinite-scroll-distance="timelineCtrl.show_items-1">
						<div ng-if="timelineCtrl.timeline.length > 0" ng-cloak>
							<div class="col-xs-12 timeline-box" ng-repeat="timeline in timelineCtrl.timeline | filter:{ person: {person_type:timelineCtrl.selectedPersonType} }" ng-cloak>
								<div class="menu-category list-group" >
									<div class="row timeline-header">
										<img class="avatar-logued-user" ng-src="{{ timelineCtrl.parseImage(timeline.person.url_avatar)}}">
										<span class="timeline-person"><a ng-href="{{ timeline.person.main_link }}"><span ng-bind="timeline.person.name"></span></a></span>
										<span class="timeline-action" ng-bind="timeline.action_name"></span>
										<span class="timeline-time" am-time-ago="timeline.date | amUtc"></span>
									</div>
									<div class="row timeline-img">
										<img class="col-xs-12 grid-image responsive" ng-src="{{timeline.photo}}">
									</div>
									<div class="row text-right">
										<span class="timeline-loved" ng-bind="timeline.loveds"></span>
										<span ng-if="timeline.isLoved" class="icons-hover heart-icon heart-red-icon" ng-click="timelineCtrl.unLoveTimeline(timeline)" ng-cloak></span>
										<span ng-if="!timeline.isLoved" class="icons-hover heart-icon heart-black-icon" ng-click="timelineCtrl.loveTimeline(timeline)" ng-cloak></span>
									</div>
									<div class="row">
										<span class="timeline-title"><a ng-href="{{ timeline.link }}"><span ng-bind-html="timeline.title"></span></a></span>
									</div>
									<div class="row">
										<span class="timeline-description" ng-bind-html="timeline.description"></span>
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