<?php
use app\assets\desktop\admin\InfluencersAsset;
use app\models\Person;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
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
						'template' => "{products} {view} {update} {delete}",
						'buttons' => [
							'view' => function($url, $model, $key) {
								$url = Url::to(["influencer/about", "slug" => $model->slug, 'influencer_id' => $model->short_id]);
								return Html::a('<span class="glyphicon glyphicon-user fc-fff fs1"></span>', $url);
							},

							'update' => function($url, $model, $key) {
								$url = Url::to(["influencer/about-edit", "slug" => $model->slug, 'influencer_id' => $model->short_id]);
								return Html::a('<span class="glyphicon glyphicon-edit fc-fff fs1"></span>', $url);
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
							return $model->personalInfoMapping->getBrandName();
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
