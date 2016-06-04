<?php
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\helpers\Json;
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
$lang = Yii::$app->language;

?>

<div class="row no-gutter" ng-controller="faqCtrl" ng-init="init()">

	<?php $this->registerJs("var _answersAndQuestions = " . Json::encode($answersAndQuestions) . ";", View::POS_END); ?>

	<div class="col-xs-12 no-padding flex flex-column">

		<div class="search-header-content fc-fff">
			<div class="cols-xs12 fs-upper flex flex-align-center flex-column flex-justify-center search-header">
				<div class="fs3-857"><h1><?= Yii::t('app/public', 'Help & Faq') ?></h1></div>

				<div class="search_holder fs0-857">
					<input type="text" class="funiv_bold fs1-143 fc-4e" name="search" placeholder="WHAT IS YOUR QUESTION ABOUT?">
					<span class="pointer glyphicon-content bc-c7 fc-fff">
						<span class="glyphicon glyphicon-search">

					</span>
				</div>

			</div>
		</div>

		<div class="col-xs-12 flex">
			<div class="column-left col-xs-2 funiv_bold fs-upper bc-e9">
				<div class="column-header fs1-357 fc-fff bc-d8 flex flex-align-center"><span><?= Yii::t('app/public', 'FAQ') ?></span></div>
				<div>
					<ul class="fs0-857 funiv_bold">
						<?php foreach ($answersAndQuestions as $answerQuestion){ ?>
						<li id="answer-<?= $answerQuestion['short_id'] ?>" class="menu-entry"><?= $answerQuestion['question'] ?></li>
						<?php } ?>
					</ul>
				</div>
			</div>

			<div class="fpf central-text-content">
				<div ng-repeat="(questid, oneAnswQuest) in answersAndQuestions">
					<div class="question-content fs0-857 funiv_bold fs-upper" ng-click="showAnswer = ! showAnswer">
						<span class="glyphicon glyphicon-plus-sign fc-c7"></span>
						<span class="underline question">{{oneAnswQuest.question}}</span>
					</div>
					<div class="answer" ng-show="showAnswer"><span>{{oneAnswQuest.answer}}</span></div>
				</div>

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
