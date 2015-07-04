<?php
use yii\web\View;
use app\models\Lang;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use app\helpers\Utils;
use yii\grid\GridView;
use app\models\MetricType;
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
	<div class="col-xs-12 no-horizontal-padding">

		<?php $this->registerJs("var _categories = " . Json::encode($categories) . ";", View::POS_HEAD) ?>
		<?php $this->registerJs("var _sizecharts_template = " . Json::encode($sizechart_template) . ";", View::POS_HEAD) ?>

		<div class="row no-gutter page-title-row bgcolor-3d">
			<div class="row-same-height">
				<div class="col-xs-4 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Size charts"); ?></h2>
				</div>

				<div class="col-xs-4 col-height col-middle flex flex-align-center">
					<div class="funiv fs-upper fc-9b fs0-786 flex-inline"><?php echo Yii::t("app/admin", "Filter by category"); ?></div>
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

				<div class="col-xs-4 col-height col-middle">
					<button class="btn btn-default btn-purple fc-fff funiv fs0-786 fs-upper pull-right" ng-click="create_new()"><?php echo Yii::t("app/admin", "Create new"); ?></button>
				</div>
			</div>
		</div>

		<?php
			echo GridView::widget([
				'id' => 'size_charts_list',
				'dataProvider' => $sizecharts,
				'options' => [
					'class' => 'funiv fc-fff fs1-071',
				],
				'columns' => [
					[
						'value' => function($model){
							return Utils::getValue($model->name, Yii::$app->language, array_keys(Lang::EN_US)[0]);
						},
						'label' => Yii::t("app/admin", "Name")
					],
					[
						'value' => function($model) {
							return Yii::t("app/admin", MetricType::TXT[$model->metric_unit]);
						},
						'label' => Yii::t("app/admin", "Metric unit")
					],
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => "{update} {delete}",
						'buttons' => [
							'update' => function($url, $model, $key) {
								$url = Url::to(["/admin/size-chart", "size_chart_id" => $model->short_id]);
								return Html::a('<span class="glyphicon glyphicon-pencil fc-68 fs1"></span>', $url);
							},

							'delete' => function($url, $model, $key) {
								return Html::tag("span", "", [
									"class" => "pointer glyphicon glyphicon-trash fc-68 fs1",
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
	<form novalidate name="form" class="popup-size-chart">
		<div class='modal-header'>
			<h3 class='modal-title funiv fs1'><?php echo Yii::t("app/admin", "Create new size chart"); ?></h3>
		</div>
		<div class='modal-body'>
			<label class="modal-title funiv fs1 fnormal fc-18"><?php echo Yii::t("app/admin", "Size table name"); ?></label>
			<div class="input-group" ng-repeat="(lang_k, lang_v) in data.langs">
				<span class="input-group-addon funiv_bold fs1 fs-upper" id="basic-addon-{{ $index }}">{{ lang_v }}</span>
				<input required="" type="text" class="form-control funiv fs1" placeholder="<?php echo Yii::t("app/admin", "Option name..."); ?>" aria-describedby="basic-addon-{{ $index }}" ng-model="langs[lang_k]" name="{{ lang_k }}">
				<span class="input-group-addon alert-danger funiv fs0-929" ng-show="form.$submitted && !form.$valid && !form['{{lang_k}}'].$valid">
					<span ng-show="form['{{lang_k}}'].$error.required"><?php echo Yii::t("app/admin", "Required!"); ?></span>
				</span>
			</div>
			<br />

			<label class="modal-title funiv fs1 fnormal fc-18"><?php echo Yii::t("app/admin", "Based on existing size chart"); ?></label>
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
			<button class='btn btn-light-green fc-18 funiv fs0-786 fs-upper' ng-click='form.$submitted = true; form.$valid && ok()'><?php echo Yii::t("app/admin", "Confirm"); ?></button>
			<button class='btn btn-gray fc-fff funiv fs-upper fs0-786' ng-click='cancel()' type="submit"><?php echo Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>
