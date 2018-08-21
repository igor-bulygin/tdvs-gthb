<?php

use app\assets\desktop\admin\InfluencersAsset;
use app\models\Person;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $influencers yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = [
	'label' => 'Influencers',
	'url' => ['/admin/influencers']
];

InfluencersAsset::register($this);

$this->title = 'Todevise / Admin / Influencers';
?>

<div class="row no-gutter" ng-controller="influencersCtrl as influencersCtrl">
	<div class="col-xs-12 no-horizontal-padding">

		<?php $this->registerJs("var _DEVISER = " . Json::encode(Person::DEVISER) . ";", View::POS_HEAD); ?>

		<div class="row no-gutter page-title-row">
			<div class="row-same-height">
				<div class="col-xs-2 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Influencers"); ?></h2>
				</div>
				<div class="col-xs-6 col-height col-middle flex flex-align-center">

				</div>
<!--				<div class="col-xs-4 col-height col-middle">-->
<!--					<button class="btn btn-default btn-purple fc-fff funiv fs0-786 fs-upper pull-right" ng-click="influencersCtrl.create_new()">--><?//= Yii::t("app/admin", "Create new"); ?><!--</button>-->
<!--				</div>-->
			</div>
		</div>

		<?php
			echo GridView::widget([
				'id' => 'influencers_list',
				'dataProvider' => $influencers,
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
						'template' => "{view} {update} {settings} {states} {change_email} {delete}",
						'buttons' => [
							'view' => function($url, $model, $key) {
								$url = $model->getAboutLink();
								return Html::a('<span class="glyphicon glyphicon-user fc-fff fs1"></span>', $url);
							},

							'update' => function($url, $model, $key) {
								$url = $model->getAboutEditLink();
								return Html::a('<span class="glyphicon glyphicon-edit fc-fff fs1"></span>', $url);
							},

							'settings' => function($url, $model, $key) {
								$url = $model->getSettingsLink();
								return Html::a('<span class="glyphicon glyphicon-cog fc-fff fs1"></span>', $url);
							},

							'states' => function($url, $model, $key) {
								if ($model->account_state == Person::ACCOUNT_STATE_BLOCKED) {
									return Html::tag("span", "", [
										"class" => "pointer glyphicon glyphicon-ok-circle fc-fff fs1",
										"title" => "Activate influencer (draft)",
										"ng-click" => "influencersCtrl.draft('$model->short_id')"
									]);
								} else {
									return Html::tag("span", "", [
										"class" => "pointer glyphicon glyphicon-remove-circle fc-fff fs1",
										"title" => "Block influencer",
										"ng-click" => "influencersCtrl.block('$model->short_id')"
									]);
								}
							},

							'change_email' => function($url, $model, $key) {
								return Html::tag("span", "", [
									"class" => "pointer glyphicon glyphicon-envelope fc-fff fs1",
									"title" => "Change email",
									"ng-click" => "influencersCtrl.change_email('$model->short_id')"
								]);
							},

							'delete' => function($url, $model, $key) {
								return Html::tag("span", "", [
									"class" => "pointer glyphicon glyphicon-trash fc-fff fs1",
									"ng-click" => "influencersCtrl.delete('$model->short_id')"
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
					[
						'value' => function($model) {
							/** @var Person $model */
							return $model->account_state;
						},
						'header' => Html::tag("div", Yii::t("app/admin", "Status")),
					],
				]
			]);
		?>

	</div>
</div>


<script type="text/ng-template" id="template/modal/influencer/change_email.html">
	<form novalidate name="influencerChangeEmailCtrl.form" class="popup-new-deviser">
		<div class='modal-header'>
			<h3 class='modal-title funiv fs1'><?= Yii::t("app/admin", "Set influencer email."); ?></h3>
		</div>
		<div class='modal-body'>
			<label class="modal-title funiv fs1 fnormal fc-18"><?= Yii::t("app/admin", "Email"); ?></label>
			<div class="input-group">
				<input id="name" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Set influencer email..."); ?>" ng-model="influencerChangeEmailCtrl.data.email" name="email">
				<span class="input-group-addon alert-danger funiv fs0-929" id="basic-addon-name" ng-show="influencerChangeEmailCtrl.form.$submitted && !influencerChangeEmailCtrl.form.$valid && !influencerChangeEmailCtrl.form['email'].$valid"></span>
			</div>
			<br />
		</div>
		<div class='modal-footer'>
			<button type="button" class='btn btn-danger' ng-click='influencerChangeEmailCtrl.form.$submitted = true; influencerChangeEmailCtrl.form.$valid && influencerChangeEmailCtrl.ok()'><?= Yii::t("app/admin", "Confirm"); ?></button>
			<button type="button" class='btn btn-primary' ng-click='influencerChangeEmailCtrl.cancel()' type="submit"><?= Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>

