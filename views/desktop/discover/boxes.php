<?php

\app\assets\desktop\discover\GlobalAsset::register($this);

$this->title = Yii::t('app/public','EXPLORE_BOXES');

?>
<div class="our-devisers-wrapper" ng-controller="exploreBoxesCtrl as exploreBoxesCtrl">
	<div class="container">
		<div class="our-boxes-header">
			<h3 translate="discover.EXPLORE_BOXES"></h3>
			<h5 class="text-center" translate="discover.boxes.DISCOVER_LATEST_TRENDS"></h5>
			<div class="row">
				<form name="exploreBoxesCtrl.form">
					<div class="devisers-searcher">
						<input type="text" class="form-control white-rounded-input" name="key" ng-model="exploreBoxesCtrl.key" on-press-enter="exploreBoxesCtrl.search(exploreBoxesCtrl.form)" placeholder="{{ 'discover.SEARCH_KEYWORD' | translate }}" ng-cloak>
						<span class="ion-search" ng-click="exploreBoxesCtrl.search(exploreBoxesCtrl.form)"></span>
					</div>
				</form>
			</div>
		</div>
		<div class="our-devisers-body">
			<div class="col-md-2">
				<explore-boxes-filters filters="exploreBoxesCtrl.filters" searching="exploreBoxesCtrl.searching"></explore-boxes-filters>
			</div>
			<div class="col-md-10">
				<div class="found-header">
					<p ng-if="exploreBoxesCtrl.search_key && !exploreBoxesCtrl.searching" ng-cloak translate="discover.WE_FOUND_X_RESULTS" translate-values="{ counter: exploreBoxesCtrl.results.meta.total_count, keys:exploreBoxesCtrl.search_key }"></p>
				</div>
				<hr />
				<div ng-if="exploreBoxesCtrl.searching" ng-cloak>
					<p class="text-center" translate="discover.SEARCHING"></p>
				</div>
				<div ng-if="exploreBoxesCtrl.results.items.length === 0" ng-cloak>
					<p class="text-center" translate="discover.boxes.NO_BOXES_FOUND"></p>
				</div>
				<explore-boxes-results results="exploreBoxesCtrl.results" ng-if="exploreBoxesCtrl.results && exploreBoxesCtrl.results.items.length > 0" ng-cloak></explore-boxes-results>
			</div>
		</div>
	</div>
</div>