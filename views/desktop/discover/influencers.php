<?php

$this->title = 'Discover influencers - Todevise';

\app\assets\desktop\discover\DiscoverAsset::register($this);

$this->registerJs("var type = 3", yii\web\View::POS_HEAD, 'person-type-script');

?>

<div class="container" ng-controller="discoverCtrl as discoverCtrl">
	<div class="row">
		<h3>Our incluencers</h3>
		<h5 class="text-center" style="color: white;">Discover talented influencers from around the globe</h5>
	</div>
	<div class="row">
	<form name="discoverCtrl.form">
		<div class="col-md-4 col-md-offset-4">
			<input type="text" class="form-control" name="key" ng-model="discoverCtrl.key" on-press-enter="discoverCtrl.search(discoverCtrl.form)" placeholder="Search keyword">
		</div>
	</form>
	</div>
	<div class="row">
		<p ng-if="discoverCtrl.key">We found <span ng-bind="discoverCtrl.results.meta.total_count"></span> influencers with the keywords "<span ng-bind="discoverCtrl.key"></span>"</p>
	</div>
	<div class="row">
		<div class="col-md-2">
			<discover-filters filters="discoverCtrl.filters"></discover-filters>
		</div>
	</div>
	<div class="col-md-10">
		<discover-results results="discoverCtrl.results" ng-if="discoverCtrl.results" ng-cloak></discover-results>
	</div>
</div>