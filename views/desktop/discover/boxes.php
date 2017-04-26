<?php

$this->title = 'Explore boxes - Todevise';

\app\assets\desktop\discover\ExploreBoxesAsset::register($this);

?>
<div class="our-devisers-wrapper" ng-controller="exploreBoxesCtrl as exploreBoxesCtrl">
	<div class="container">
		<div class="our-boxes-header">
			<h3>Explore boxes</h3>
			<h5 class="text-center">Its time to discover the latest trends</h5>
			<div class="row">
				<form name="exploreBoxesCtrl.form">
					<div class="devisers-searcher">
						<input type="text" class="form-control white-rounded-input" name="key" ng-model="exploreBoxesCtrl.key" on-press-enter="exploreBoxesCtrl.search(exploreBoxesCtrl.form)" placeholder="Search keyword">
						<span class="ion-search"></span>
					</div>
				</form>
			</div>
		</div>
		<div class="our-devisers-body">
			<div class="col-md-2"><explore-boxes-filters filters="exploreBoxesCtrl.filters" searching="exploreBoxesCtrl.searching"></explore-boxes-filters></div>
			<div class="col-md-10">
				<div class="found-header">
					<p ng-if="exploreBoxesCtrl.search_key" ng-cloak>We found <span ng-bind="exploreBoxesCtrl.results.meta.total_count"></span> boxes with the keywords "<span class="key" ng-bind="exploreBoxesCtrl.search_key"></span>"</p>
				</div>
				<hr />
				<div ng-if="exploreBoxesCtrl.searching" ng-cloak>
					<p class="text-center">Searching...</p>
				</div>
				<div ng-if="exploreBoxesCtrl.results.items.length === 0" ng-cloak>
					<p class="text-center">No boxes found with the specified search criteria.</p>
				</div>
				<explore-boxes-results results="exploreBoxesCtrl.results" ng-if="exploreBoxesCtrl.results && exploreBoxesCtrl.results.items.length > 0" ng-cloak></explore-boxes-results>
			</div>
		</div>
	</div>
</div>