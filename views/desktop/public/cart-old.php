<?php
use app\assets\desktop\pub\CartAsset;
use yii\widgets\Pjax;


/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Cart',
	'url' => ['/public/cart']
];

CartAsset::register($this);

$this->title = 'Todevise / Cart';
?>

<div class="row no-gutter">
	<div class="col-xs-12 no-padding cart">

		<?php

			Pjax::begin([
				'enablePushState' => false
			]);
			// echo ListView::widget([
			// 	'dataProvider' => $products,
			// 	'itemView' => '_category_product',
			// 	'itemOptions' => [
			// 		'tag' => false
			// 	],
			// 	'options' => [
			// 		'class' => 'products_wrapper'
			// 	],
			// 	'layout' => '<div class="funiv fs1-143 fc-6d">{summary}</div><div class="products_holder">{items}</div>{pager}',
			// ]);

			Pjax::end();

		?>

	</div>
</div>
