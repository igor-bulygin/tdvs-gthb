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
$this->registerJs("var searchParam = '".$text."'", yii\web\View::POS_HEAD, 'products-search-script');
/* \app\assets\desktop\pub\ProductsAsset::register($this); */
\app\assets\desktop\discover\GlobalAsset::register($this);



?>

<div ng-controller="exploreProductsCtrl as exploreProductsCtrl">
	<div class="container store">
		<div>
			<div class="col-md-12">
				<explore-products-filters searching="exploreProductsCtrl.searching" results="exploreProductsCtrl.results" limit="exploreProductsCtrl.limit" ></explore-products-filters>
				<div>
					<div ng-if="exploreProductsCtrl.results.items.length === 0 && !exploreProductsCtrl.searching" ng-cloak>
						<p class="text-center" translate="NO_PRODUCTS_FOUND"></p>
					</div>
					<div>
						<explore-products-results ng-if="exploreProductsCtrl.results.length != 0" results="exploreProductsCtrl.results"  limit="exploreProductsCtrl.limit" ></explore-products-results>
					</div>
					<div ng-if="exploreProductsCtrl.searching" ng-cloak>
						<p class="text-center" translate="SEARCHING"></p>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>