<?php
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use app\assets\desktop\pub\CategoryAsset;


/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Category',
	'url' => ['/public/category']
];

CategoryAsset::register($this);

$this->title = 'Todevise / Category';
?>

<div class="row no-gutter">
	<div class="col-xs-12 no-padding products">

		<?php

			Pjax::begin([
				'enablePushState' => false,
				'timeout' => 5000
			]);

			echo ListView::widget([
				'dataProvider' => $products,
				'itemView' => '_category_product',
				'itemOptions' => [
					'tag' => false
				],
				'options' => [
					'class' => 'products_wrapper'
				],
				'layout' => '<div class="funiv fs1-143 fc-6d">{summary}</div><div class="products_holder">{items}</div>{pager}',
			]);

			Pjax::end();

		?>

	</div>
</div>
