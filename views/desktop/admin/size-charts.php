<?php
use app\models\MetricUnit;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use app\helpers\Utils;
use yii\grid\GridView;
use app\assets\desktop\admin\SizeChartsAsset;

/* @var $this yii\web\View */
/* @var $categories ArrayObject */
/* @var $sizechart_template ArrayObject */
/* @var $sizecharts yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = [
	'label' => 'Size charts',
	'url' => ['/admin/size-charts']
];

SizeChartsAsset::register($this);

$this->title = 'Todevise / Admin / Size charts';
?>

<div class="row no-gutter" ng-controller="sizeChartsCtrl" ng-init="init()">
	<div class="col-sm-12 col-md-12 col-lg-12">

		<?php $this->registerJs("var _categories = " . Json::encode($categories) . ";", View::POS_HEAD) ?>
		<?php $this->registerJs("var _sizecharts_template = " . Json::encode($sizechart_template) . ";", View::POS_HEAD) ?>

		<div class="row">
			<div class="col-md-4 col-lg-4">
				<h2><?= Yii::t("app/admin", "Size charts"); ?></h2>
			</div>
			<div class="col-md-4 col-lg-4">
				<div
					angular-multi-select
					api="api"
					id-property="short_id"
					input-model="categories"
					output-model="selectedCategories"

					group-property="sub"
					tick-property="check"

					item-label="{{ name[lang] }}"
					selection-mode="single"
					search-property="name"
					min-search-length="3"
					hidden-property="hidden"
					helper-elements="noall nonone noreset nofilter"
				></div>
			</div>
			<div class="col-md-4 col-lg-4">
				<button class="btn btn-default" ng-click="create_new()">Create new</button>
			</div>
		</div>

		<?php
			echo GridView::widget([
				'id' => 'size_charts_list',
				'dataProvider' => $sizecharts,
				'columns' => [
					[
						'value' => function($model){
							return Utils::getValue($model->name, Yii::$app->language, "en-US");
						},
						'label' => Yii::t("app/admin", "Name")
					],
					[
						'value' => function($model){
							$v = $model->metric_units;
							if($v == MetricUnit::NONE) {
								return Yii::t("app/admin", "None");
							} else if($v == MetricUnit::SIZE) {
								return Yii::t("app/admin", "Size");
							} else if($v == MetricUnit::WEIGHT) {
								return Yii::t("app/admin", "Weight");
							}
						},
						'label' => Yii::t("app/admin", "Metric units")
					],
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => "{update} {delete}",
						'buttons' => [
							'update' => function($url, $model, $key) {
								$url = Url::to(["/admin/size-chart", "size_chart_id" => $model->short_id]);
								return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
							},

							'delete' => function($url, $model, $key) {
								return Html::tag("span", "", [
									"class" => "pointer glyphicon glyphicon-trash",
									"ng-click" => "delete('$model->short_id')"
								]);
							}
						],
						'header' => Html::tag("div", Yii::t("app/admin", "Actions"))
					]
				]
			]);
		?>

	</div>
</div>

<script type="text/ng-template" id="template/modal/sizechart/create_new.html">
	<form novalidate name="form">
		<div class='modal-header'>
			<h3 class='modal-title'><?php echo Yii::t("app/admin", "Create new size chart"); ?></h3>
		</div>
		<div class='modal-body'>
			<label><?php echo Yii::t("app/admin", "Size table name"); ?></label>
			<div class="input-group" ng-repeat="(lang_k, lang_v) in data.langs">
				<span class="input-group-addon" id="basic-addon-{{ $index }}">{{ lang_v }}</span>
				<input required="" type="text" class="form-control" placeholder="<?php echo Yii::t("app/admin", "Option name..."); ?>" aria-describedby="basic-addon-{{ $index }}" ng-model="langs[lang_k]" name="{{ lang_k }}">
				<span class="input-group-addon alert-danger" ng-show="form.$submitted && !form.$valid && !form['{{lang_k}}'].$valid">
					<span ng-show="form['{{lang_k}}'].$error.required">Required!</span>
				</span>
			</div>
			<br />

			<label><?php echo Yii::t("app/admin", "Based on existing size chart"); ?></label>
			<div class="input-group">
				<div
					angular-multi-select
					id-property="short_id"
					input-model="data.sizecharts"
					output-model="selectedSizeChartTemplate"
					tick-property="check"
					item-label="{{ name[data.lang] }}"
					selection-mode="single"
					search-property="name[data.lang]"
					min-search-length="3"
					helper-elements="noall nonone noreset filter"
					button-template="angular-multi-select-btn-data.htm"
					button-label="{{ name[data.lang] }}"
					></div>
			</div>

		</div>
		<div class='modal-footer'>
			<button class='btn btn-success' ng-click='form.$submitted = true; form.$valid && ok()'><?php echo Yii::t("app/admin", "Confirm"); ?></button>
			<button class='btn btn-primary' ng-click='cancel()' type="submit"><?php echo Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>