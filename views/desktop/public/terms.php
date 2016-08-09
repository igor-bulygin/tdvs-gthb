<?php
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use app\assets\desktop\pub\TermsAsset;


/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'TERMS',
	'url' => ['/public/terms']
];

TermsAsset::register($this);


$this->title = 'Todevise / TERMS';
$lang = Yii::$app->language;

?>

	<div class="row no-gutter" ng-controller="termsCtrl as termsCtrl">

		<?php $this->registerJs("var _groupOfTerms = " . Json::encode($groupOfTerms) . ";", View::POS_END); ?>

			<div class="col-xs-12 no-padding flex flex-column">

				<div class="search-header-content fc-fff">
					<div class="cols-xs12 fs-upper flex flex-align-center flex-column flex-justify-center search-header">
						<div class="fs3-857 funiv_thin">
							<?= Yii::t('app/public', 'TERMS') ?>
						</div>

						<div class="search_holder fs0-857">
							<input type="text" class="funiv_bold fs1-143 fc-4e" name="search" placeholder="WHAT IS YOUR QUESTION ABOUT?">
							<span class="pointer glyphicon-content bc-c7 fc-fff">
						<span class="glyphicon glyphicon-search"></span>
							</span>
						</div>

					</div>
				</div>

				<div class="col-xs-12 flex">
					<div class="column-left col-xs-2 funiv_bold fs-upper bc-e9">
						<div class="column-header fs1-357 fc-fff bc-d8 flex flex-align-center"><span><?= Yii::t('app/public', 'TERM') ?></span></div>
						<div>
							<ul class="fs0-857 funiv_bold" ng-repeat="answersAndQuestions in termsCtrl.groupOfTerms">
								<li id="" class="menu-entry" ng-click="termsCtrl.showTerms(answersAndQuestions.short_id)" ng-bind="answersAndQuestions.title"></li>
							</ul>
						</div>
					</div>

					<div class="fpf central-text-content">
						<div ng-repeat="answersAndQuestions in termsCtrl.groupOfTerms">
							<div ng-if="termsCtrl.activeTermId == answersAndQuestions.short_id">
								<div ng-repeat="(questid, oneAnswQuest) in answersAndQuestions.terms">
									<div class="question-content fs0-857 funiv_bold fs-upper" ng-init="oneAnswQuest.sign='plus'" ng-click="oneAnswQuest.showAnswer = !oneAnswQuest.showAnswer; oneAnswQuest.sign = oneAnswQuest.showAnswer ? 'minus' : 'plus'">
										<span class="glyphicon glyphicon-{{oneAnswQuest.sign}}-sign fc-c7"></span>
										<span class="underline question" ng-bind="oneAnswQuest.question"></span>
									</div>
									<div class="answer" ng-show="oneAnswQuest.showAnswer"><span ng-bind="oneAnswQuest.answer"></span></div>
								</div>
							</div>
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