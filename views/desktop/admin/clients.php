<?php

use app\assets\desktop\admin\ClientsAsset;
use app\models\Person;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $clients yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = [
	'label' => 'clients',
	'url' => ['/admin/clients']
];

ClientsAsset::register($this);

$this->title = 'Todevise / Admin / Clients';
?>

<div class="row no-gutter" ng-controller="clientsCtrl as clientsCtrl">
	<div class="col-xs-12 no-horizontal-padding">

		<?php $this->registerJs("var _DEVISER = " . Json::encode(Person::DEVISER) . ";", View::POS_HEAD); ?>

		<div class="row no-gutter page-title-row">
			<div class="row-same-height">
				<div class="col-xs-2 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Clients"); ?></h2>
				</div>
				<div class="col-xs-6 col-height col-middle flex flex-align-center">

				</div>
<!--				<div class="col-xs-4 col-height col-middle">-->
<!--					<button class="btn btn-default btn-purple fc-fff funiv fs0-786 fs-upper pull-right" ng-click="clientsCtrl.create_new()">--><?//= Yii::t("app/admin", "Create new"); ?><!--</button>-->
<!--				</div>-->
			</div>
		</div>

		<?php
			echo GridView::widget([
				'id' => 'clients_list',
				'dataProvider' => $clients,
				'options' => [
					'class' => 'funiv fc-fff fs1-071',
				],
				'tableOptions' => [
					'class' => 'table table-bordered',
				],
				'pager' => [
					'options' => [
						'class' => 'pagination flex flex-justify-center'
					]
				],
				'columns' => [
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => "{view} {states} {delete}",
						'buttons' => [
							'view' => function($url, $model, $key) {
								$url = $model->getAboutLink();
								return Html::a('<span class="glyphicon glyphicon-user fc-fff fs1"></span>', $url);
							},

							'states' => function($url, $model, $key) {
								if ($model->account_state == Person::ACCOUNT_STATE_BLOCKED) {
									return Html::tag("span", "", [
										"class" => "pointer glyphicon glyphicon-ok-circle fc-fff fs1",
										"title" => "Activate client",
										"ng-click" => "clientsCtrl.draft('$model->short_id')"
									]);
								} else {
									return Html::tag("span", "", [
										"class" => "pointer glyphicon glyphicon-remove-circle fc-fff fs1",
										"title" => "Block client",
										"ng-click" => "clientsCtrl.block('$model->short_id')"
									]);
								}
							},

							'delete' => function($url, $model, $key) {
								return Html::tag("span", "", [
									"class" => "pointer glyphicon glyphicon-trash fc-fff fs1",
									"ng-click" => "clientsCtrl.delete('$model->short_id')"
								]);
							}
						],
						'header' => Html::tag("div", Yii::t("app/admin", "Actions")),

						'headerOptions' => [
							'class' => 'text-center'
						],
						'contentOptions' => [
							'class' => 'text-center'
						]
					],
					[
						'value' => function($model){
							/** @var Person $model */
							return $model->getName();
						},
						'label' => Yii::t("app/admin", "Name")
					],
					[
						'value' => function($model){
							return $model->credentials["email"];
						},
						'label' => Yii::t("app/admin", "Email")
					],
				]
			]);
		?>

	</div>
</div>
