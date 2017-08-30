<?php

\app\assets\desktop\discover\GlobalAsset::register($this);

$this->title = Yii::t('app/public','Discover influencers - Todevise');
$this->registerJs("var type = 3", yii\web\View::POS_HEAD, 'person-type-script');

?>
<div class="our-devisers-wrapper" ng-controller="discoverCtrl as discoverCtrl">
	<div class="container">
		<div class="our-influencers-header">
			<h3 translate="discover.influencers.OUR_INFLUENCERS"></h3>
			<h5 class="text-center" translate="discover.influencers.DISCOVER_INFLUENCERS"></h5>
			<div class="row">
				<form name="discoverCtrl.form">
					<div class="devisers-searcher">
						<input type="text" class="form-control white-rounded-input" name="key" ng-model="discoverCtrl.key" on-press-enter="discoverCtrl.search(discoverCtrl.form)" placeholder="{{ 'discover.SEARCH_KEYWORD' | translate }}" ng-cloak>
						<span class="ion-search"></span>
					</div>
				</form>
			</div>
		</div>
		<div class="our-devisers-body">
			<div class="col-md-2">
				<discover-filters filters="discoverCtrl.filters" searching="discoverCtrl.searching"></discover-filters>
			</div>
			<div class="col-md-10">
				<div class="found-header">
					<p ng-if="discoverCtrl.search_key && !
				<div ng-if="" ng-cloak translate="discover.WE_FOUND_X_RESULTS" translate-values="{ counter: discoverCtrl.results.meta.total_count }">"<span class="key" ng-bind="discoverCtrl.search_key"></span>"</p>
				</div>
				<hr />
				<div ng-if="discoverCtrl.searching" ng-cloak>
					<p class="text-center" translate="discover.SEARCHING"></p>
				</div>
				<discover-results results="discoverCtrl.results" ng-if="discoverCtrl.results && discoverCtrl.results.items.length > 0" ng-cloak></discover-results>
			</div>
		</div>
	</div>
</div>