<?php

$this->title = 'Explore boxes - Todevise';

\app\assets\desktop\discover\ExploreBoxesAsset::register($this);

?>
<div ng-controller="exploreBoxesCtrl as exploreBoxesCtrl">
	<div class="container">
		<h5 class="text-center">Explore boxes</h5>
		<div class="row">
			<form name="exploreBoxesCtrl.form">
				<input type="text" class="form-control white-rounded-input" name="key" ng-model="exploreBoxesCtrl.key" on-press-enter="exploreBoxesCtrl.search(exploreBoxesCtrl.form)" placeholder="Search keyword">
			</form>
		</div>
		<div>
			<div class="col-md-2"><explore-boxes-filters filters="exploreBoxesCtrl.filters"></explore-boxes-filters></div>
			<div class="col-md-10">
				<div ng-if="exploreBoxesCtrl.search_key" ng-cloak>
					<p>We found <span ng-bind="exploreBoxesCtrl.results.meta.total_count"></span> boxes with the keywords "<span class="key" ng-bind="exploreBoxesCtrl.search_key"></span>"</p>
					<hr />
				</div>
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