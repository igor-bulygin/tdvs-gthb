<?php

$this->title = 'Discover devisers - Todevise';

\app\assets\desktop\discover\DiscoverAsset::register($this);

$this->registerJs("var type = 2", yii\web\View::POS_HEAD, 'person-type-script');

?>

<div class="container" ng-controller="discoverCtrl as discoverCtrl">
	<div class="our-devisers-header">
		<h3>Our devisers</h3>
		<h5 class="text-center">Discover innovative and talented creators from around the globe</h5>
		<div class="row">
			<form name="discoverCtrl.form">
				<div class="devisers-searcher">
					<input type="text" class="form-control white-rounded-input" name="key" ng-model="discoverCtrl.key" on-press-enter="discoverCtrl.search(discoverCtrl.form)" placeholder="Search keyword">
					<span class="ion-search"></span>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2">
			<discover-filters filters="discoverCtrl.filters"></discover-filters>
		</div>
		<div class="col-md-10">
			<div class="row">
				<p ng-if="discoverCtrl.key">We found <span ng-bind="discoverCtrl.results.meta.total_count"></span> devisers with the keywords "<span ng-bind="discoverCtrl.key"></span>"</p>
			</div>
			<hr />
			<discover-results results="discoverCtrl.results" ng-if="discoverCtrl.results" ng-cloak></discover-results>
		</div>
	</div>
</div>