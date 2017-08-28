<?php

\app\assets\desktop\discover\GlobalAsset::register($this);

$this->title = Yii::t('app/public','Stories - Todevise');

?>
<div class="our-devisers-wrapper" ng-controller="exploreStoriesCtrl as exploreStoriesCtrl">
	<div class="container">
		<div class="our-boxes-header">
			<h3 translate="STORIES"></h3>
			<h5 class="text-center" translate="SEE_THE_WORLD"></h5>
			<div class="row">
				<form name="exploreStoriesCtrl.form">
					<div class="devisers-searcher">
						<input type="text" class="form-control white-rounded-input" name="key" ng-model="exploreStoriesCtrl.key" on-press-enter="exploreStoriesCtrl.search(exploreStoriesCtrl.form)" placeholder="{{ 'SEARCH_KEYWORD' | translate }}" ng-cloak>
						<span class="ion-search"></span>
					</div>
				</form>
			</div>
		</div>
		<div class="our-devisers-body">
			<div class="col-md-2">
				<explore-stories-filters filters="exploreStoriesCtrl.filters" searching="exploreStoriesCtrl.searching"></explore-stories-filters></div>
				<div class="col-md-10">
					<div class="found-header">
						<p ng-if="exploreStoriesCtrl.search_key && !exploreStoriesCtrl.searching" ng-cloak translate="WE_FOUND_X_RESULTS" translate-values="{ counter: exploreStoriesCtrl.results.meta.total_count }">"<span class="key" ng-bind="exploreStoriesCtrl.search_key"></span>"</p>
					</div>
					<hr />
					<div ng-if="exploreStoriesCtrl.searching" ng-cloak>
						<p class="text-center" translate="SEARCHING"></p>
					</div>
					<div ng-if="exploreStoriesCtrl.results.items.length === 0" ng-cloak>
						<p class="text-center" translate="NO_STORIES_FOUNDED"></p>
					</div>
					<div ng-if="exploreStoriesCtrl.results.items.length != 0" ng-cloak>
						<explore-stories-results results="exploreStoriesCtrl.results" ng-if="exploreStoriesCtrl.results.items.length > 0" ng-cloak></explore-stories-results>
					</div>
				</div>
			</div>
	</div>
</div>