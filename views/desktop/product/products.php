<?php

/* @var \app\models\Product[] $products */
/* @var \app\models\Product[][] $moreWork */
$this->title = 'Works - Todevise';

/* \app\assets\desktop\pub\ProductsAsset::register($this); */
\app\assets\desktop\discover\GlobalAsset::register($this);

?>

<div class="our-devisers-wrapper" ng-controller="exploreProductsCtrl as exploreProductsCtrl">
	<div class="container">
		<div class="our-devisers-body">
			<div class="col-md-2">
				<explore-products-filters filters="exploreProductsCtrl.filters" searching="exploreProductsCtrl.searching"></explore-stories-filters></div>
				<div class="col-md-10">
					<div class="found-header">
						<p ng-if="exploreProductsCtrl.search_key" ng-cloak>We found <span ng-bind="exploreProductsCtrl.results.meta.total_count"></span> products with the keywords "<span class="key" ng-bind="exploreProductsCtrl.search_key"></span>"</p>
					</div>
					<hr />
					<div ng-if="exploreProductsCtrl.searching" ng-cloak>
						<p class="text-center">Searching...</p>
					</div>
					<div ng-if="exploreProductsCtrl.results.items.length === 0" ng-cloak>
						<p class="text-center">No products found with the specified search criteria.</p>
					</div>
					<div ng-if="exploreProductsCtrl.results.items.length != 0" ng-cloak>
						<explore-products-results results="exploreProductsCtrl.results" ng-if="exploreProductsCtrl.results.items.length > 0" ng-cloak></explore-products-results>
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