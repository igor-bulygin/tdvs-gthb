<?php

$this->title = 'Discover influencers - Todevise';

\app\assets\desktop\discover\DiscoverAsset::register($this);

$this->registerJs("var type = 3", yii\web\View::POS_HEAD, 'person-type-script');

?>

<div class="container" ng-controller="discoverCtrl as discoverCtrl">
	<div class="our-influencers-header">
		<h3>Our influencers</h3>
		<h5 class="text-center">Discover talented influencers from around the globe</h5>
		<div class="row">
			<form name="discoverCtrl.form">
				<div class="devisers-searcher">
					<input type="text" class="form-control white-rounded-input" name="key" ng-model="discoverCtrl.key" on-press-enter="discoverCtrl.search(discoverCtrl.form)" placeholder="Search keyword">
					<span class="ion-search"></span>
				</div>
			</form>
		</div>
	</div>
	<div class="our-devisers-body">
		<div class="col-md-2">
			<discover-filters filters="discoverCtrl.filters"></discover-filters>
		</div>
	</div>
	<div class="col-md-10">
		<div class="found-header">
			<p ng-if="discoverCtrl.search_key">We found <span ng-bind="discoverCtrl.results.meta.total_count"></span> influencers with the keywords "<span class="key" ng-bind="discoverCtrl.search_key"></span>"</p>
		</div>
		<discover-results results="discoverCtrl.results" ng-if="discoverCtrl.results" ng-cloak></discover-results>
	</div>
</div>