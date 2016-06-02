<?php
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use app\assets\desktop\pub\StatictextAsset;
//use app\assets\desktop\pub\TermsAsset;


/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Terms',
	'url' => ['/public/terms']
];

StatictextAsset::register($this);

$this->title = 'Todevise / Terms';

// TODO: Maybe get questions and answers from db
?>

<div class="row no-gutter">
	<div class="col-xs-12 no-padding terms">

		<div class="search-header-content fc-fff">
			<div class="cols-xs12 fs3-857 fs-upper flex flex-align-center search-header">
				<div class="center-justify"><h1><?= Yii::t('app/public', 'Terms & Conditions') ?></h1></div>
			</div>
		</div>

		<div class="col-xs-12 flex flex-prop-1">
			<div class="column-left col-xs-2 funiv_bold fs-upper bc-e9">
				<div>
					<ul class="fs0-857 funiv_bold">
						<li>topic 1</li>
						<li>topic 2</li>
					</ul>
				</div>
			</div>
		</div>

    <div class="fpf">

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
