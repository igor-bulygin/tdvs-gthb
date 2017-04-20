<?php

$this->title = 'Stories - Todevise';

\app\assets\desktop\discover\ExploreStoriesAsset::register($this);

?>
<div class="our-devisers-wrapper" ng-controller="exploreStoriesCtrl as exploreStoriesCtrl">
	<div class="container">
		<div class="our-boxes-header">
			<h3>Stories</h3>
			<h5 class="text-center">See the world from a whole different perspective</h5>
			<div class="row">
				<form name="exploreStoriesCtrl.form">
					<div class="devisers-searcher">
						<input type="text" class="form-control white-rounded-input" name="key" ng-model="exploreStoriesCtrl.key" on-press-enter="exploreStoriesCtrl.search(exploreStoriesCtrl.form)" placeholder="Search keyword">
						<span class="ion-search"></span>
					</div>
				</form>
			</div>
		</div>
		<div class="our-devisers-body">
			<div class="col-md-2"><explore-stories-filters filters="exploreStoriesCtrl.filters"></explore-stories-filters></div>
			<div class="col-md-10">
				<div class="found-header">
					<p ng-if="exploreStoriesCtrl.search_key" ng-cloak>We found <span ng-bind="exploreStoriesCtrl.results.meta.total_count"></span> stories with the keywords "<span class="key" ng-bind="exploreStoriesCtrl.search_key"></span>"</p>
				</div>
				<hr />
				<div ng-if="exploreStoriesCtrl.searching" ng-cloak>
					<p class="text-center">Searching...</p>
				</div>
				<div ng-if="exploreBoxesCtrl.results.items.length === 0" ng-cloak>
					<p class="text-center">No stories found with the specified search criteria.</p>
				</div>
                <div ng-if="exploreBoxesCtrl.results.items.length != 0" ng-cloak>
				    <explore-stories-results results="exploreStoriesCtrl.results" ng-if="exploreStoriesCtrl.results && exploreStoriesCtrl.results.items.length > 0" ng-cloak></explore-stories-results>
                </div>
			</div>
		</div>
	</div>
</div>