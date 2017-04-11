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
				<div class="boxes-wrapper">
					<div ng-repeat="product in box.products" ng-show="$index < 3">
						<a ng-href="{{product.link}}">
							<img class="grid-image" ng-src="{{product.box_photo}}" ng-attr-alt="{{product.name}}" ng-attr-title="{{product.name}}">
						</a>
					</div>
					<a ng-href="{{box.link}}" class="group-box-title"><span ng-bind="box.name"></span> (<span ng-bind="box.products.length"></span>)</a>
				</div>
			</div>
		</div>
	</div>
</div>