<?php
use app\assets\desktop\admin\ProductsAsset;
use app\helpers\Utils;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

$this->params['breadcrumbs'][] = [
	'label' => 'Products',
	'url' => ['/' . $deviser['slug'] . '/products']
];

ProductsAsset::register($this);

$this->title = 'Todevise / Admin / Products';
?>

	<?php $this->registerJs("var _deviser = " . Json::encode($deviser) . ";", View::POS_HEAD); ?>

		<div class="row no-gutter" ng-controller="productsCtrl as productsCtrl">
			<div class="col-xs-12 no-horizontal-padding">

				<div class="row no-gutter page-title-row">
					<div class="row-same-height">
						<div class="col-xs-2 col-height col-middle">
							<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Products"); ?></h2>
						</div>
						<div class="col-xs-6 col-height col-middle flex flex-align-center">

						</div>
						<div class="col-xs-4 col-height col-middle">
							<button class="btn btn-default btn-purple fc-fff funiv fs0-786 fs-upper pull-right" ng-click="productsCtrl.new_product()">
								<?= Yii::t("app/admin", "Create new"); ?>
							</button>
						</div>
					</div>
				</div>

				<?php
			echo GridView::widget([
				'id' => 'products_list',
				'dataProvider' => $products,
				'options' => [
					'class' => 'funiv fc-fff fs1-071',
				],
				'columns' => [
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => "{view} {edit} {delete}",
						'buttons' => [

							'view' => function($url, $model, $key) use ($deviser) {
								$url = Url::to(["product/detail", "slug" => Utils::l($model->slug), "product_id" => $model->short_id]);
								return Html::a('<span class="glyphicon glyphicon-eye-open fc-fff fs1"></span>', $url);
							},

							'edit' => function($url, $model, $key) use ($deviser) {
								$url = Url::to(["product/edit", "slug" => $deviser['slug'], "deviser_id"=> $deviser['short_id'], "product_id" => $model['short_id']]);
								return Html::a('<span class="glyphicon glyphicon-edit fc-fff fs1"></span>', $url);
							},

							'delete' => function($url, $model, $key) {
								return Html::tag("span", "", [
									"class" => "pointer glyphicon glyphicon-trash fc-fff fs1",
									"ng-click" => "productsCtrl.delete_product('$model->short_id')"
								]);
							}
						],
						'header' => Html::tag("div", Yii::t("app/admin", "Actions")),

						'headerOptions' => [
							'class' => 'text-center'
						],
						'contentOptions' => [
							'class' => 'text-center'
						],
					],
					[
						'value' => function($model){
							return Utils::l($model->name);
						},
						'label' => Yii::t("app/admin", "Name")
					]
				]
			]);
		?>

			</div>
		</div>