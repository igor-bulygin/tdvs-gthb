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
			<div class="col-xs-12 col-md-3"><nav class="timeline-menu"><ul>
				<li ng-class="{'active': !timelineCtrl.selectedPersonType}">
					<a style="cursor:pointer;" ng-class="{'active': !timelineCtrl.selectedPersonType}" ng-click="timelineCtrl.resetFilter()" translate="person.ALL"></a>
				</li>
				<li ng-repeat="personType in timelineCtrl.personTypes" ng-class="{'active': timelineCtrl.selectedPersonType === personType.value }">
					<a style="cursor:pointer;" ng-class="{'active': timelineCtrl.selectedPersonType === personType.value }" translate="{{personType.name}}" ng-click="timelineCtrl.selectedPersonType = personType.value"></a>
				</li>
			</ul></nav></div>
			<div class="col-xs-12 col-md-8 col-lg-7 tl-pad">
				<div class="timeline-wrapper">
					<div class="col-xs-10 col-xs-offset-1 mt-40 mb-40" ng-if="(timelineCtrl.timeline | filter:{ person: {person_type:timelineCtrl.selectedPersonType}}).length < 1" ng-cloak>
						<span translate="person.timeline.EMPTY_TIMELINE"></span>
					</div>
					<div ng-if="(timelineCtrl.timeline | filter:{ person: {person_type:timelineCtrl.selectedPersonType}}).length > 0" infinite-scroll="timelineCtrl.addMoreItems()" infinite-scroll-distance="timelineCtrl.show_items-1">
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
										<a ng-href="{{ timeline.link }}">
											<img class="col-xs-12 grid-image responsive" ng-src="{{timeline.photo}}">
										</a>
									</div>
									<div class="row text-right">
										<span class="timeline-loved" ng-bind="timeline.loveds"></span>
										<span ng-if="timeline.isLoved" class="icons-hover heart-icon heart-red-icon" ng-click="timelineCtrl.unLoveTimeline(timeline)" ng-cloak></span>
										<span ng-if="!timeline.isLoved" class="icons-hover heart-icon heart-black-icon" ng-click="timelineCtrl.loveTimeline(timeline)" ng-cloak></span>
									</div>
									<div class="row">
										<span class="timeline-title"><a ng-href="{{ timeline.link }}"><span ng-bind-html="timeline.title"></span></a></span>
									</div>									
									<uib-accordion>										
										<div uib-accordion-group is-open="group.open">
											<uib-accordion-heading >
												<div ng-if="!group.open">
													<div class="row" >
														<span class="timeline-description" ng-bind-html="timeline.description" style="text-transform: none !important;font-weight: normal !important;"></span>
													</div>
													<span class="col-xs-12 text-center red-text" style="text-transform: none !important;font-weight: normal !important;margin-top:10px;" translate="todevise.SEE_MORE"></span>
												</div>
												<div ng-if="group.open">
													<span class="col-xs-12 text-center red-text" style="text-transform: none !important;font-weight: normal !important;margin-top:25px;margin-bottom:10px;" translate="todevise.SEE_LESS"></span>
												</div>
											</uib-accordion-heading>
											<span ng-bind-html="timeline.description"></span>
										</div>										
									</uib-accordion>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>