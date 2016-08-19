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

	<div class="row no-gutter" ng-controller="sizeChartsCtrl as sizeChartsCtrl">
		<div class="col-xs-12 no-horizontal-padding">

			<?php $this->registerJs("var _categories = " . Json::encode($categories) . ";", View::POS_HEAD) ?>
				<?php $this->registerJs("var _sizecharts_template = " . Json::encode($sizechart_template) . ";", View::POS_HEAD) ?>

					<div class="row no-gutter page-title-row bgcolor-3d">
						<div class="row-same-height">
							<div class="col-xs-4 col-height col-middle">
								<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Size charts"); ?></h2>
							</div>

							<div class="col-xs-4 col-height col-middle flex flex-align-center">
								<div class="funiv fs-upper fc-9b fs0-786 flex-inline">
									<?= Yii::t("app/admin", "Filter by category"); ?>
								</div>
								<div angular-multi-select input-model="sizeChartsCtrl.categories" output-model="sizeChartsCtrl.selectedCategories" name="categories" id-property="short_id" checked-property="check" children-property="sub" dropdown-label="<[ '<(name[&quot;{{ sizeChartsCtrl.lang }}&quot;])>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/admin', 'Select category') ?>']>" node-label="<[ name['{{ sizeChartsCtrl.lang }}'] ]>" leaf-label="<[ name['{{ sizeChartsCtrl.lang }}'] ]>" max-checked-leafs="1" hide-helpers="check_all, check_none, reset"></div>
							</div>

							<div class="col-xs-4 col-height col-middle">
								<button class="btn btn-default btn-purple fc-fff funiv fs0-786 fs-upper pull-right" ng-click="sizeChartsCtrl.create_new()">
									<?= Yii::t("app/admin", "Create new"); ?>
								</button>
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
							return Utils::l($model->name);
						},
						'label' => Yii::t("app/admin", "Name")
					],
					[
						'value' => function($model) {
							foreach(MetricType::UNITS as $index => $type) {
								if(in_array($model->metric_unit, $type)) return Yii::t("app/admin", MetricType::TXT[$index]);
							}
						},
						'label' => Yii::t("app/admin", "Metric type")
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
									"ng-click" => "sizeChartsCtrl.delete('$model->short_id')"
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
		<form novalidate name="create_newCtrl.form" class="popup-size-chart">
			<div class='modal-header'>
				<h3 class='modal-title funiv fs1'><?= Yii::t("app/admin", "Create new size chart"); ?></h3>
			</div>
			<div class='modal-body'>
				<label class="modal-title funiv fs1 fnormal fc-18">
					<?= Yii::t("app/admin", "Size table name"); ?>
				</label>
				<div class="input-group" ng-repeat="(lang_k, lang_v) in create_newCtrl.data.langs">
					<span class="input-group-addon funiv_bold fs1 fs-upper" id="basic-addon-{{ $index }}">{{ lang_v }}</span>
					<input required="" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Option name... "); ?>" aria-describedby="basic-addon-{{ $index }}" ng-model="create_newCtrl.langs[lang_k]" name="{{ lang_k }}">
					<span class="input-group-addon alert-danger funiv fs0-929" ng-show="create_newCtrl.form.$submitted && !create_newCtrl.form.$valid && !create_newCtrl.form['{{lang_k}}'].$valid">
					<span ng-show="create_newCtrl.form['{{lang_k}}'].$error.required"><?= Yii::t("app/admin", "Required!"); ?></span>
					</span>
				</div>
				<br />

				<label class="modal-title funiv fs1 fnormal fc-18">
					<?= Yii::t("app/admin", "Based on existing size chart"); ?>
				</label>
				<div class="input-group">
					<div angular-multi-select input-model="create_newCtrl.data.sizecharts" output-model="create_newCtrl.selectedSizeChartTemplate" id-property="short_id" checked-property="check" children-property="sub" dropdown-label="<[ '<(name[&quot;{{ create_newCtrl.data.lang }}&quot;])>' | outputModelIterator : this : ', ']>" node-label="<[ name['{{ create_newCtrl.data.lang }}'] ]>" leaf-label="<[ name['{{ create_newCtrl.data.lang }}'] ]>" max-checked-leafs="1" hide-helpers="check_all, check_none, reset"></div>
				</div>

			</div>
			<div class='modal-footer'>
				<button class='btn btn-light-green fc-18 funiv fs0-786 fs-upper' ng-click='create_newCtrl.form.$submitted = true; create_newCtrl.form.$valid && ok()'>
					<?= Yii::t("app/admin", "Confirm"); ?>
				</button>
				<button class='btn btn-grey fc-fff funiv fs-upper fs0-786' ng-click='create_newCtrl.cancel()' type="submit">
					<?= Yii::t("app/admin", "Cancel"); ?>
				</button>
			</div>
		</form>
	</script>