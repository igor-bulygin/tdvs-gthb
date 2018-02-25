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

<explore-products></explore-products>
