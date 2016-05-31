<?php
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use app\assets\desktop\pub\FaqAsset;


/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'FAQ',
	'url' => ['/public/faq']
];

FaqAsset::register($this);

$this->title = 'Todevise / FAQ';

// TODO: Maybe get questions and answers from db
?>

<div class="row no-gutter">
	<div class="col-xs-12 no-padding faq">

    <div class="column-left">
      <div class="column-header"><span><?= Yii::t('app/public', 'FAQ') ?></span></div>
      <div>
        <ul>
          <li>quest 1</li>
          <li>quest 2</li>
        </ul>
      </div>
    </div>

    <div class="wrapper">
			<?php foreach ($answersAndQuestions as $answerQuestion){ ?>
      	<div class="question"><span><?= $answerQuestion['question'] ?></span></div>
      	<div class="answer"><span><?= $answerQuestion['answer'] ?></span></div>
			<?php } ?>
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
