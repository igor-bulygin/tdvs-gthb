<?php

/* @var \app\models\Product[] $products */
/* @var \app\models\Product[][] $moreWork */
if ($text) {
	$this->title = Yii::t('app/public',
		'WORKS_SEARCH_PARAM',
		['search_param' => $text]
	);
	
} else {
	$this->title = Yii::t('app/public', 'WORKS');
}
Yii::$app->opengraph->title = $this->title;

$this->registerJs("var searchParam = '".$text."'", yii\web\View::POS_HEAD, 'products-search-script');
/* \app\assets\desktop\pub\ProductsAsset::register($this); */
\app\assets\desktop\discover\GlobalAsset::register($this);



?>

<style>
#product-results_A { __width: 930px; margin: 0 auto; __column-count: 5; __column-gap: 10px; __-webkit-column-count: 5; __-webkit-column-gap: 10px; __-moz-column-count: 5; __-moz-column-gap: 10px; }
.product-result-item_A { display: inline-block; margin-bottom: 0px; __width: 100%; padding: 0 !important; }
.product-result-item_A figure, .product-result-item figcaption { padding: 0 !important; }
</style>
<div ng-controller="mainSearcherCtrl as mainSearcherCtrl">
	<div class="results-wrapper" style="padding-top:20px;">
		<div id="categoryFilter" class="container store" ng-if="mainSearcherCtrl.counted">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-if="mainSearcherCtrl.searchTypeId == 100">
				<a style="color:#1C1919; cursor:pointer;"><span ng-repeat="searchType in mainSearcherCtrl.searchTypes" class="col-xs-3 col-sm-3 col-md-1-5 col-lg-1-5 text-center" ng-if="searchType.num > 0" ng-click="mainSearcherCtrl.selectSearchType(searchType)" ng-class="mainSearcherCtrl.searchTypeClass(searchType.id)" ng-cloak>{{ searchType.name | translate}} ({{ searchType.num }})</span></a>
			</div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-if="mainSearcherCtrl.searchTypeId != 100" ng-cloak>
                <h4>Search in &laquo;{{ mainSearcherCtrl.currentSearchType.name | translate }}&raquo;</h4>
            </div>
		</div>
	</div>
	<div class="col-xs-12">
		<div ng-switch on="mainSearcherCtrl.currentSearchType.id">
			<explore-products ng-switch-when="1"></explore-products>
            <explore-products ng-switch-when="100"></explore-products>
			<explore-boxes searchdata="mainSearcherCtrl.searchdata" ng-switch-when="2"></explore-boxes>
			<explore-person searchdata="mainSearcherCtrl.searchdata" hideHeader="mainSearcherCtrl.hideHeader" ng-switch-when="3"></explore-person>
			<explore-person searchdata="mainSearcherCtrl.searchdata" hideHeader="mainSearcherCtrl.hideHeader" ng-switch-when="4"></explore-person>
		</div>
	</div>
</div>
