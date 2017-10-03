<?php

\app\assets\desktop\discover\GlobalAsset::register($this);

$this->title = Yii::t('app/public','DISCOVER_INFLUENCERS');
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
						<input type="text" class="form-control white-rounded-input" name="key" ng-model="discoverCtrl.key" on-press-enter="discoverCtrl.search()" placeholder="{{ 'discover.SEARCH_KEYWORD' | translate }}" ng-cloak>
						<span class="ion-search" ng-click="discoverCtrl.search()"></span>
					</div>
				</form>
			</div>
		</div>
		<div class="our-devisers-body" style="padding-bottom:100px;">
			<div class="col-md-2">
				<discover-filters filters="discoverCtrl.filters" searching="discoverCtrl.searching"></discover-filters>
			</div>
			<div class="col-md-10">
				<div class="found-header">
					<p ng-if="discoverCtrl.search_key && (!discoverCtrl.searching || discoverCtrl.results.items.length > 0)" ng-cloak><span translate="discover.WE_FOUND_X_RESULTS_WITH_KEY" translate-values="{ counter: discoverCtrl.results_found, keys: discoverCtrl.search_key}"></span></p>
					<p ng-if="!discoverCtrl.search_key && (!discoverCtrl.searching || discoverCtrl.results.items.length > 0)" ng-cloak><span translate="discover.WE_FOUND_X_RESULTS" translate-values="{ counter: discoverCtrl.results_found}"></span></p>
				</div>
				<div class="mt-30 col-md-12 col-md-offset-4" ng-if="discoverCtrl.searching && discoverCtrl.results.items.length == 0" ng-cloak>
					<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
				</div>
				<discover-results results="discoverCtrl.results" ng-if="discoverCtrl.results && discoverCtrl.results.items.length > 0" ng-cloak></discover-results>
				<div class="text-center col-md-12 mt-30" ng-if="discoverCtrl.results_found > discoverCtrl.results.items.length && (!discoverCtrl.searching || discoverCtrl.results.items.length > 0)" ng-cloak >
					<button class="big-btn btn btn-default" ng-click="discoverCtrl.searchMore()" ng-disabled="discoverCtrl.searching">
						<span translate="discover.SEE_MORE" ng-if="!discoverCtrl.searching"></span>
						<i class="fa fa-spinner fa-pulse fa-3x fa-fw small" ng-if="discoverCtrl.searching"></i>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>