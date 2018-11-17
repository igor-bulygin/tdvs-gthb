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
    .search-loading {text-align: center; padding-bottom: 20px;}
</style>
<div ng-controller="mainSearcherCtrl as mainSearcherCtrl">
	<div class="results-wrapper" style="padding-top:20px;">
		<div id="categoryFilter" class="container store" ng-if="mainSearcherCtrl.counted">
            <div class="results-header">
                <div class="items-count"
                     ng-if="mainSearcherCtrl.searchParam && mainSearcherCtrl.totalItemsCount > 0"
                     translate="discover.FOUND_X_RESULTS_WITH_KEY"
                     translate-values="{ counter: mainSearcherCtrl.totalItemsCount, keys: mainSearcherCtrl.searchParam }"
                ></div>
                <div class="items-count"
                     ng-if="!mainSearcherCtrl.searchParam && mainSearcherCtrl.totalItemsCount > 0"
                     translate="discover.FOUND_X_RESULTS"
                     translate-values="{ counter: mainSearcherCtrl.totalItemsCount }"
                ></div>
                <div class="products-order" ng-if="(mainSearcherCtrl.currentSearchType.id === 1) || (mainSearcherCtrl.currentSearchType.id === 100 && mainSearcherCtrl.firstExistingSearchType.id == 1)">
                    <ol class="nya-bs-select" title="Sort by" ng-model="mainSearcherCtrl.orderFilter" ng-change="mainSearcherCtrl.orderProducts()" ng-cloak>
                        <li nya-bs-option="type in mainSearcherCtrl.orderTypes">
                            <a href="#"><span>{{ type.name | translate }}</span></a>
                        </li>
                    </ol>
                </div>
                <div class="clearfix"></div>
            </div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-if="mainSearcherCtrl.searchTypeId == 100">
				<a style="color:#1C1919; cursor:pointer;">
                    <span
                            ng-repeat="searchType in mainSearcherCtrl.searchTypes"
                            class="col-xs-2 col-sm-2 col-md-1-5 col-lg-1-5 text-center"
                            ng-if="searchType.num > 0"
                            ng-click="mainSearcherCtrl.selectSearchType(searchType)"
                            ng-class="mainSearcherCtrl.searchTypeClass(searchType.id)"
                            ng-cloak
                    >
                        {{ searchType.name | translate}} ({{ searchType.num }})
                    </span>
                </a>
			</div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-if="mainSearcherCtrl.searchTypeId != 100" ng-cloak>
                <h4>Search in &laquo;{{ mainSearcherCtrl.currentSearchType.name | translate }}&raquo;</h4>
            </div>
		</div>
	</div>
	<div class="col-xs-12">
        <div class="mt-20 search-loading" ng-if="!mainSearcherCtrl.counted" ng-cloak>
            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
        </div>

		<div ng-if="mainSearcherCtrl.counted">

            <explore-products ng-if="mainSearcherCtrl.currentSearchType.id == 1"></explore-products>
            <explore-products ng-if="mainSearcherCtrl.currentSearchType.id == 100 && mainSearcherCtrl.firstExistingSearchType.id == 1"></explore-products>

			<explore-boxes searchdata="mainSearcherCtrl.searchdata" ng-if="mainSearcherCtrl.currentSearchType.id == 2"></explore-boxes>
            <explore-boxes searchdata="mainSearcherCtrl.searchdata" ng-if="mainSearcherCtrl.currentSearchType.id == 100 && mainSearcherCtrl.firstExistingSearchType.id == 2"></explore-boxes>

            <explore-person searchdata="mainSearcherCtrl.searchdata" hideHeader="mainSearcherCtrl.hideHeader" ng-if="mainSearcherCtrl.currentSearchType.id == 3"></explore-person>
            <explore-person searchdata="mainSearcherCtrl.searchdata" hideHeader="mainSearcherCtrl.hideHeader" ng-if="mainSearcherCtrl.currentSearchType.id == 100 && mainSearcherCtrl.firstExistingSearchType.id == 3"></explore-person>

            <explore-person searchdata="mainSearcherCtrl.searchdata" hideHeader="mainSearcherCtrl.hideHeader" ng-if="mainSearcherCtrl.currentSearchType.id == 4"></explore-person>
            <explore-person searchdata="mainSearcherCtrl.searchdata" hideHeader="mainSearcherCtrl.hideHeader" ng-if="mainSearcherCtrl.currentSearchType.id == 100 && mainSearcherCtrl.firstExistingSearchType.id == 4"></explore-person>

            <explore-person searchdata="mainSearcherCtrl.searchdata" hideHeader="mainSearcherCtrl.hideHeader" ng-if="mainSearcherCtrl.currentSearchType.id == 5"></explore-person>
            <explore-person searchdata="mainSearcherCtrl.searchdata" hideHeader="mainSearcherCtrl.hideHeader" ng-if="mainSearcherCtrl.currentSearchType.id == 100 && mainSearcherCtrl.firstExistingSearchType.id == 5"></explore-person>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 40px 0;" ng-if="mainSearcherCtrl.currentSearchType.id == 100 && mainSearcherCtrl.firstExistingSearchType.id === undefined" ng-cloak>
                <p class="text-center" translate="discover.NOTHING_FOUND"></p>
            </div>


		</div>
	</div>
</div>
