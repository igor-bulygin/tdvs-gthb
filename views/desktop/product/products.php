<?php

/* @var \app\models\Product[] $products */
/* @var \app\models\Product[][] $moreWork */
if ($text) {
	$this->title = Yii::t('app/public',
		'Works - {search_param} - Todevise',
		['search_param' => $text]
	);
} else {
	$this->title = Yii::t('app/public', 'Works - Todevise');
}

/* \app\assets\desktop\pub\ProductsAsset::register($this); */
\app\assets\desktop\discover\GlobalAsset::register($this);

$this->registerJs("var searchParam = '".$text."'", yii\web\View::POS_HEAD, 'products-search-script');

?>

<div ng-controller="exploreProductsCtrl as exploreProductsCtrl">
	<div class="container store">
		<div>
			<div class="col-md-12">
				<explore-products-filters searching="exploreProductsCtrl.searching" results="exploreProductsCtrl.results"></explore-products-filters>
				<div>
					<div ng-if="exploreProductsCtrl.searching" ng-cloak>
						<p class="text-center" translate="SEARCHING"></p>
					</div>
					<div ng-if="exploreProductsCtrl.results.items.length === 0" ng-cloak>
						<p class="text-center" translate="NO_PRODUCTS_FOUND"></p>
					</div>
					<div>
						<explore-products-results ng-if="exploreProductsCtrl.results.items.length != 0 && !exploreProductsCtrl.searching" ng-cloak results="exploreProductsCtrl.results" ng-if="exploreProductsCtrl.results.items.length > 0" ng-cloak></explore-products-results>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>
<!--
<section class="grid-wrapper">
	<div class="container">
		<p class="text-primary"><?=$total?> results <?=($text ? 'of <b>'.$text.'</b>' : '')?></p>
		<div id="macy-container"><?=$htmlWorks?></div>
	</div>
	<form id="formPagination">
		<input type="hidden" id="text" name="text" value="<?=$text?>" />
		<input type="hidden" id="page" name="page" value="<?=$page?>" />
		<input type="hidden" id="more" name="more" value="<?=$more?>" />
	</form>
</section>