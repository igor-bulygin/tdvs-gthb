<?php

use app\assets\desktop\admin\DevisersAsset;
use app\models\Person;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $countries ArrayObject */
/* @var $devisers yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = [
	'label' => 'Devisers',
	'url' => ['/admin/devisers']
];

DevisersAsset::register($this);

$this->title = 'Todevise / Admin / Devisers';
?>

<div class="row no-gutter" ng-controller="devisersCtrl as devisersCtrl">
	<div class="col-xs-12 no-horizontal-padding">

		<?php $this->registerJs("var _DEVISER = " . Json::encode(Person::DEVISER) . ";", View::POS_HEAD); ?>

		<div class="row no-gutter page-title-row">
			<div class="row-same-height">
				<div class="col-xs-2 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Devisers"); ?></h2>
				</div>
				<div class="col-xs-6 col-height col-middle flex flex-align-center">

				</div>
<!--				<div class="col-xs-4 col-height col-middle">-->
<!--					<button class="btn btn-default btn-purple fc-fff funiv fs0-786 fs-upper pull-right" ng-click="devisersCtrl.create_new()">--><?//= Yii::t("app/admin", "Create new"); ?><!--</button>-->
<!--				</div>-->
			</div>
		</div>

		<?php
			echo GridView::widget([
				'id' => 'devisers_list',
				'dataProvider' => $devisers,
				'options' => [
					'class' => 'funiv fc-fff fs1-071',
				],
				'pager' => [
					'options' => [
						'class' => 'pagination flex flex-justify-center'
					]
				],
				'columns' => [
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => "{products} {view} {update} {states} {fee} {delete} ",
						'buttons' => [
							'products' => function($url, $model, $key) {
								$url = $model->getStoreLink();
								return Html::a('<span class="glyphicon glyphicon-th fc-fff fs1"></span>', $url);
							},

							'view' => function($url, $model, $key) {
								$url = $model->getAboutLink();
								return Html::a('<span class="glyphicon glyphicon-user fc-fff fs1"></span>', $url);
							},

							'update' => function($url, $model, $key) {
								$url = $model->getAboutEditLink();
								return Html::a('<span class="glyphicon glyphicon-edit fc-fff fs1"></span>', $url);
							},

							'states' => function($url, $model, $key) {
								if ($model->account_state == Person::ACCOUNT_STATE_BLOCKED) {
									return Html::tag("span", "", [
										"class" => "pointer glyphicon glyphicon-ok-circle fc-fff fs1",
										"title" => "Activate deviser (draft)",
										"ng-click" => "devisersCtrl.draft('$model->short_id')"
									]);
								} else {
									return Html::tag("span", "", [
										"class" => "pointer glyphicon glyphicon-remove-circle fc-fff fs1",
										"title" => "Block deviser",
										"ng-click" => "devisersCtrl.block('$model->short_id')"
									]);
								}
							},

							'fee' => function($url, $model, $key) {
								return Html::tag("span", "", [
									"class" => "pointer glyphicon glyphicon-euro fc-fff fs1",
									"ng-click" => "devisersCtrl.fee('$model->short_id')"
								]);
							},

							'delete' => function($url, $model, $key) {
								return Html::tag("span", "", [
									"class" => "pointer glyphicon glyphicon-trash fc-fff fs1",
									"ng-click" => "devisersCtrl.delete('$model->short_id')"
								]);
							},
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
					[
						'value' => function($model) use ($countries_lookup){
							/** @var Person $model */
							if (array_key_exists($model->personalInfoMapping->country, $countries_lookup)) {
								return $countries_lookup[$model->personalInfoMapping->country];
							}
							return null;
						},
						'header' => Html::tag("div", Yii::t("app/admin", "Country")),
					],
					[
						'value' => function($model) {
							/** @var Person $model */
							return $model->account_state;
						},
						'header' => Html::tag("div", Yii::t("app/admin", "Status")),
					],
					[
						'value' => function($model) {
							/** @var Person $model */
							if ($model->application_fee) {
								return round($model->application_fee * 100, 2) . '%';
							}

							return 'Default';
						},
						'header' => Html::tag("div", Yii::t("app/admin", "Application fee")),
					]
				]
			]);
		?>

	</div>
</div>

<script type="text/ng-template" id="template/modal/deviser/fee.html">
	<form novalidate name="deviserFeeCtrl.form" class="popup-new-deviser">
		<div class='modal-header'>
			<h3 class='modal-title funiv fs1'><?= Yii::t("app/admin", "Set deviser fee percentage."); ?></h3>
		</div>
		<div class='modal-body'>
			<label class="modal-title funiv fs1 fnormal fc-18"><?= Yii::t("app/admin", "Percentage. Example: set 0.145 for 14.5%, or leave empty to use default setting"); ?></label>
			<div class="input-group">
				<input id="name" type="number" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Set custom percentage..."); ?>" ng-model="deviserFeeCtrl.data.application_fee" name="application_fee">
				<span class="input-group-addon alert-danger funiv fs0-929" id="basic-addon-name" ng-show="deviserFeeCtrl.form.$submitted && !deviserFeeCtrl.form.$valid && !deviserFeeCtrl.form['application_fee'].$valid"></span>
			</div>
			<br />
		</div>
		<div class='modal-footer'>
			<button type="button" class='btn btn-danger' ng-click='deviserFeeCtrl.form.$submitted = true; deviserFeeCtrl.form.$valid && deviserFeeCtrl.ok()'><?= Yii::t("app/admin", "Confirm"); ?></button>
			<button type="button" class='btn btn-primary' ng-click='deviserFeeCtrl.cancel()' type="submit"><?= Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>
