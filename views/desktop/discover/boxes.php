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
						<input type="text" class="form-control white-rounded-input" name="key" ng-model="exploreBoxesCtrl.key" on-press-enter="exploreBoxesCtrl.search()" placeholder="{{ 'discover.SEARCH_KEYWORD' | translate }}" ng-cloak>
						<span class="ion-search" ng-click="exploreBoxesCtrl.search()"></span>
					</div>
				</form>
			</div>
		</div>
		<div class="our-devisers-body" style="padding-bottom:100px;">
			<div class="col-md-2">
				<explore-boxes-filters filters="exploreBoxesCtrl.filters" searching="exploreBoxesCtrl.searching"></explore-boxes-filters>
			</div>
			<div class="col-md-10">
				<div class="found-header">
					<p ng-if="exploreBoxesCtrl.search_key && !exploreBoxesCtrl.searching" ng-cloak translate="discover.WE_FOUND_X_RESULTS_WITH_KEY" translate-values="{ counter: exploreBoxesCtrl.results_found, keys:exploreBoxesCtrl.search_key }"></p>
					<p ng-if="!exploreBoxesCtrl.search_key && !exploreBoxesCtrl.searching" ng-cloak translate="discover.WE_FOUND_X_RESULTS" translate-values="{ counter: exploreBoxesCtrl.results_found }"></p>
				</div>
				<explore-boxes-results results="exploreBoxesCtrl.results" ng-if="exploreBoxesCtrl.results && exploreBoxesCtrl.results.items.length > 0" ng-cloak></explore-boxes-results>
				<div class="mt-30 col-md-12 col-md-offset-4" ng-if="exploreBoxesCtrl.searching && exploreBoxesCtrl.results.items.length == 0" ng-cloak>
					<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
				</div>
				<div class="text-center col-md-12 mt-30" ng-if="exploreBoxesCtrl.results_found > exploreBoxesCtrl.results.items.length && (!exploreBoxesCtrl.searching || exploreBoxesCtrl.results.items.length > 0)" ng-cloak >
					<button class="big-btn btn btn-default" ng-click="exploreBoxesCtrl.searchMore()" ng-disabled="exploreBoxesCtrl.searching">
						<span translate="discover.SEE_MORE" ng-if="!exploreBoxesCtrl.searching"></span>
						<i class="fa fa-spinner fa-pulse fa-3x fa-fw small" ng-if="exploreBoxesCtrl.searching"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>