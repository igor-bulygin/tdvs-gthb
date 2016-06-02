<?php
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use app\assets\desktop\pub\StatictextAsset;
use app\assets\desktop\pub\FaqAsset;


/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'FAQ',
	'url' => ['/public/faq']
];

FaqAsset::register($this);
StatictextAsset::register($this);


$this->title = 'Todevise / FAQ';

// TODO: Maybe get questions and answers from db
?>

<div class="row no-gutter">
	<div class="col-xs-12 no-padding flex flex-column">

		<div class="search-header-content fc-fff">
			<div class="cols-xs12 fs3-857 fs-upper flex flex-align-center search-header">
				<div class="center-justify"><h1><?= Yii::t('app/public', 'Help & Faq') ?></h1></div>
			</div>
		</div>

		<div class="col-xs-12 flex">
			<div class="column-left col-xs-2 funiv_bold fs-upper bc-e9">
				<div class="column-header fs1-357 fc-fff bc-d8 flex flex-align-center"><span><?= Yii::t('app/public', 'FAQ') ?></span></div>
				<div>
					<ul class="fs0-857 funiv_bold">
						<li>quest 1</li>
						<li>quest 2</li>
					</ul>
				</div>
			</div>

			<div class="fpf fs0-857">
				<?php foreach ($answersAndQuestions as $answerQuestion){ ?>
	      	<div class="question"><span><?= $answerQuestion['question'] ?></span></div>
	      	<div class="answer"><span><?= $answerQuestion['answer'] ?></span></div>
				<?php } ?>
	    </div>

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
