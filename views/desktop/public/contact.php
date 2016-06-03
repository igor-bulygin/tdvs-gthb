<?php
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use app\assets\desktop\pub\ContactAsset;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Contact',
	'url' => ['/public/contact']
];

ContactAsset::register($this);

$this->title = 'Todevise / Contact';
$lang = Yii::$app->language;

?>

<div class="row no-gutter">
	<div class="col-xs-12 no-padding flex flex-column contact">
		<div class="contact-up flex flex-row">
			<div class="contact-left flex-column">
				<div class="contact-left-top">left top</div>
				<div class="contact-left-bottom">left bottom</div>
			</div>
			<div class="contact-right">right</div>
		</div>
		<div class="contact-down flex flex-row">
			<div class="contact-down-left">left</div>
			<div class="contact-down-center">center</div>
			<div class="contact-down-right">right</div>
		</div>

		<?php

			//Pjax::begin([
			//	'enablePushState' => false
			//]);
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
      //echo $test;

			//Pjax::end();

		?>

	</div>
</div>
