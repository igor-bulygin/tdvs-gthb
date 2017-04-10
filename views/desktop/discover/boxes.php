<?php

$this->title = 'Explore boxes - Todevise';

\app\assets\desktop\discover\ExploreBoxesAsset::register($this);

?>
<div ng-controller="exploreBoxesCtrl as exploreBoxesCtrl">
	<div class="container">
		<h5 class="text-center">Explore boxes</h5>
		<div class="col-md-2"><p>Filters</p></div>
		<div class="col-md-10">
			<p>Results</p>
			<div class="col-md-4 col-xs-6 pad-grid" ng-repeat="box in exploreBoxesCtrl.boxes" ng-cloak>
				<div class="box-wrapper">
					<div ng-if="box.products.length === 1">
						<img class="grid-image" ng-src="{{box.products[0].url_image_preview}}" ng-attr-alt="{{box.products[0].name}}" ng-attr-title="{{box.products[0].name}}" style="width: 295px; height: 372px;">
					</div>
					<div ng-if="box.products.length === 2">
						<img class="grid-image" ng-src="{{box.products[0].url_image_preview}}" ng-attr-alt="{{box.products[0].name}}" ng-attr-title="{{box.products[0].name}}" style="width: 295px; height: 115px;">
						<img class="grid-image" ng-src="{{box.products[1].url_image_preview}}" ng-attr-alt="{{box.products[1].name}}" ng-attr-title="{{box.products[1].name}}" style="width: 295px; height: 257px;">
					</div>
					<div ng-if="box.products.length >= 3">
						<img class="grid-image" ng-src="{{box.products[0].url_image_preview}}" ng-attr-alt="{{box.products[0].name}}" ng-attr-title="{{box.products[0].name}}" style="width: 146px; height: 116px;">
						<img class="grid-image" ng-src="{{box.products[1].url_image_preview}}" ng-attr-alt="{{box.products[1].name}}" ng-attr-title="{{box.products[1].name}}" style="width: 145px; height: 116px;">
						<img class="grid-image" ng-src="{{box.products[2].url_image_preview}}" ng-attr-alt="{{box.products[2].name}}" ng-attr-title="{{box.products[2].name}}" style="width: 295px; height: 257px;">
					</div>
					<a ng-href="{{box.link}}" class="group-box-title"><span ng-bind="box.name"></span> (<span ng-bind="box.products.length"></span>)</a>
				</div>
			</div>
		</div>
	</div>
</div>