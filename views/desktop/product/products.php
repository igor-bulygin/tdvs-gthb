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
#product-results_A { width: 930px; margin: 0 auto; __column-count: 5; __column-gap: 10px; __-webkit-column-count: 5; __-webkit-column-gap: 10px; __-moz-column-count: 5; __-moz-column-gap: 10px; }
.product-result-item_A { display: inline-block; margin-bottom: 0px; __width: 100%; padding: 0 !important; }
.product-result-item_A figure, .product-result-item figcaption { padding: 0 !important; }
</style>

<div ng-controller="exploreProductsCtrl as exploreProductsCtrl" class="results-wrapper">
	<div class="container store">
		<div>
			<div class="col-md-12">
				<explore-products-filters searching="exploreProductsCtrl.searching" results="exploreProductsCtrl.results" limit="exploreProductsCtrl.limit" ></explore-products-filters>
				<div>
					<div ng-if="exploreProductsCtrl.results.items.length === 0 && !exploreProductsCtrl.searching" ng-cloak>
						<p class="text-center" translate="discover.NO_PRODUCTS_FOUND"></p>
					</div>
					<div class="col-md-10">
						<explore-products-results ng-if="exploreProductsCtrl.results.items.length > 0" results="exploreProductsCtrl.results"  limit="exploreProductsCtrl.limit"></explore-products-results>
					</div>
					<div class="mt-30 col-md-12 col-md-offset-4" ng-if="exploreProductsCtrl.searching && exploreProductsCtrl.results.items.length == 0" ng-cloak>
						<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
					</div>
					<div class="text-center col-md-12 mt-30" style="padding-bottom:100px;" ng-if="exploreProductsCtrl.results.counter > exploreProductsCtrl.results.items.length" ng-cloak >
						<button class="big-btn btn btn-default" ng-click="exploreProductsCtrl.searchMore()" ng-disabled="exploreProductsCtrl.searching">
							<span translate="discover.SEE_MORE" ng-if="!exploreProductsCtrl.searching"></span>
							<i class="fa fa-spinner fa-pulse fa-3x fa-fw small" ng-if="exploreProductsCtrl.searching"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
