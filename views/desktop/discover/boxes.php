<?php

$this->title = 'Explore boxes - Todevise';

\app\assets\desktop\discover\GlobalAsset::register($this);

?>
<div class="our-devisers-wrapper" ng-controller="exploreBoxesCtrl as exploreBoxesCtrl">
	<div class="container">
		<div class="our-boxes-header">
			<h3 translate="EXPLORE_BOXES"></h3>
			<h5 class="text-center" translate="DISCOVER_LATEST_TRENDS"></h5>
			<div class="row">
				<form name="exploreBoxesCtrl.form">
					<div class="devisers-searcher">
						<input type="text" class="form-control white-rounded-input" name="key" ng-model="exploreBoxesCtrl.key" on-press-enter="exploreBoxesCtrl.search(exploreBoxesCtrl.form)" placeholder="{{ 'SEARCH_KEYWORD' | translate }}" ng-cloak>
						<span class="ion-search"></span>
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
					<p ng-if="exploreBoxesCtrl.search_key" ng-cloak translate="WE_FOUND_X_RESULTS" translate-values="{ counter: exploreBoxesCtrl.results.meta.total_count }">"<span class="key" ng-bind="exploreBoxesCtrl.search_key"></span>"</p>
				</div>
				<hr />
				<div ng-if="exploreBoxesCtrl.searching" ng-cloak>
					<p class="text-center" translate="SEARCHING"></p>
				</div>
				<div ng-if="exploreBoxesCtrl.results.items.length === 0" ng-cloak>
					<p class="text-center" translate="NO_BOXES_FOUNDED"></p>
				</div>
				<explore-boxes-results results="exploreBoxesCtrl.results" ng-if="exploreBoxesCtrl.results && exploreBoxesCtrl.results.items.length > 0" ng-cloak></explore-boxes-results>
			</div>
		</div>
	</div>
</div>