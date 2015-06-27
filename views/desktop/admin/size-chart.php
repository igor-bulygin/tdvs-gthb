<?php
use yii\web\View;
use app\models\Lang;
use yii\helpers\Json;
use app\models\MetricUnit;
use app\assets\desktop\admin\SizeChartAsset;

/* @var $this yii\web\View */
/* @var $countries ArrayObject */
/* @var $categories ArrayObject */
/* @var $sizechart ArrayObject */

$this->params['breadcrumbs'][] = [
	'label' => 'Size chart',
	'url' => ['/admin/size-chart']
];

SizeChartAsset::register($this);

$this->title = 'Todevise / Admin / Size chart';
?>

<div class="row no-gutter" ng-controller="sizeChartCtrl" ng-init="init()">
	<div class="col-xs-12 no-horizontal-padding">

		<?php $this->registerJs("var _categories = " . Json::encode($categories) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _sizechart = " . Json::encode($sizechart) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _countries = " . Json::encode($countries) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _countries_lookup = " . Json::encode($countries_lookup) . ";", View::POS_HEAD); ?>
		<?php $this->registerJs("var _mus = " . Json::encode([
				["value" => MetricUnit::NONE, "text" => Yii::t("app/admin", MetricUnit::TXT[MetricUnit::NONE])],
				["value" => MetricUnit::SIZE, "text" => Yii::t("app/admin", MetricUnit::TXT[MetricUnit::SIZE])],
				["value" => MetricUnit::WEIGHT, "text" => Yii::t("app/admin", MetricUnit::TXT[MetricUnit::WEIGHT])]
			]) . ";", View::POS_HEAD); ?>

		<div class="row no-gutter page-title-row bgcolor-3d">
			<div class="row-same-height">
				<div class="col-xs-4 col-height col-middle">
					<h6 class="page-title funiv_bold fs-upper fc-fff 0-857"><?= Yii::t("app/admin", "Create size chart"); ?></h6>
				</div>
				<div class="col-md-8 col-lg-8 col-height col-middle">
					<div class="pull-right">
						<button class="btn btn btn-gray fc-fff funiv fs-upper fs0-786" ng-click="cancel()"><?php echo Yii::t("app/admin", "Cancel changes"); ?></button>
						<button class="background-none fc-fff funiv fs-upper fs0-786" ng-click="save()"><?php echo Yii::t("app/admin", "Create chart"); ?></button>
					</div>
				</div>
			</div>
		</div>

		<div class="row no-gutter flex flex-row">
			<div class="col-xs-4 div-bordered">
				<div class="checkbox padding-1">
					<label class="funiv fc-c7 fs0-929">
						<input type="checkbox" ng-model="sizechart.enabled">
						<?php echo Yii::t("app/admin", "Enabled"); ?>
					</label>
				</div>
			</div>
			<div class="col-md-4 col-lg-4 div-bordered">
				<div class="padding-1">
					<label class="funiv fc-c7 fs0-929 fnormal"><?php echo Yii::t("app/admin", "Categories"); ?></label>
					<div
						angular-multi-select
						api="api"
						id-property="short_id"
						input-model="categories"
						output-model="selectedCategories"

						group-property="sub"
						tick-property="check"

						item-label="{{ name[lang] }}"
						selection-mode="multi"
						search-property="name[lang]"
						min-search-length="3"
						hidden-property="hidden"
						helper-elements="noall nonone noreset nofilter">
					</div>
				</div>
			</div>
			<div class="col-md-4 col-lg-4 div-bordered">
				<div class="padding-1">
					<label class="funiv fc-c7 fs0-929 fnormal"><?php echo Yii::t("app/admin", "Metric unit"); ?></label>
					<div
						angular-multi-select
						api="api_mus"
						id-property="value"
						input-model="mus"
						output-model="selected_mu"
						tick-property="checked"
						item-label="{{ text }}"
						selection-mode="single"
						button-template="angular-multi-select-btn-data.htm"
						button-label="{{ text }}"
						helper-elements="noall nonone noreset nofilter">
					</div>
				</div>
			</div>
		</div>

		<br />

		<div class="row no-gutter">
			<div class="col-xs-1">
				<label class="funiv fc-c7 fs0-929 fnormal"><?php echo Yii::t("app/admin", "Countries"); ?></label>
			</div>
			<div class="col-xs-11">
				<div class="row no-gutter sortable-container" sv-root sv-part="sizechart.countries" sv-on-sort="move_country($indexFrom, $indexTo)">
					<div ng-cloak class="col-xs-2 country pull-left funiv fc-fff fs1 fs-upper" sv-element ng-repeat="country in sizechart.countries track by $index">
						<span class="glyphicon glyphicon-menu-hamburger pointer fc-68" sv-handle></span>
						{{ countries_lookup[country] }}
						<div class="pull-right">
							<span class="glyphicon glyphicon-trash pointer fc-68" ng-click="delete_country($index)"></span>
						</div>
					</div>

					<div class="col-xs-1 text-center">
						<a class="pointer fs-upper funiv fc-fff fs0-786" ng-click="new_country()"><?php echo Yii::t("app/admin", "Add new +"); ?></a>
					</div>
				</div>
			</div>
		</div>

		<div class="div-line"></div>

		<div class="row no-gutter">
			<div class="col-xs-1">
				<label class="funiv fc-c7 fs0-929 fnormal"><?php echo Yii::t("app/admin", "Columns"); ?></label>
			</div>
			<div class="col-xs-11">
				<div class="row no-gutter sortable-container" sv-root sv-part="sizechart.columns" sv-on-sort="move_column($indexFrom, $indexTo)">
					<div ng-cloak class="col-xs-2 country pull-left funiv fc-fff fs1" sv-element ng-repeat="column in sizechart.columns track by $index">
						<span class="glyphicon glyphicon-menu-hamburger pointer fc-68" sv-handle></span>
						{{ column[lang] }}
						<div class="pull-right">
							<span class="glyphicon glyphicon-trash pointer fc-68" ng-click="delete_column($index)"></span>
						</div>
					</div>

					<div class="col-xs-1 text-center">
						<a class="pointer fs-upper funiv fc-fff fs0-786" ng-click="new_column('Test')"><?php echo Yii::t("app/admin", "Add new +"); ?></a>
					</div>
				</div>
			</div>
		</div>

		<div class="div-line"></div>

		<div class="row no-gutter">
			<table class="fc-fff fs1-071 fnormal">
				<thead>
					<tr>
						<th class="fc-d6 fs0-857 fnormal" ng-cloak ng-repeat="header in table_header track by $index">{{ header }}</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-cloak ng-repeat="row in sizechart.values track by $index">
						<td ng-repeat="cell in row track by $index">
							<input value="{{ cell }}">
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<br />

		<div class="row no-gutter">
			<a class="pointer fs-upper funiv fc-fff fs0-786" ng-click="new_row()"><?php echo Yii::t("app/admin", "Add new +"); ?></a>
		</div>

	</div>
</div>

<script type="text/ng-template" id="template/modal/sizechart/create_new_country.html">
	<form novalidate name="form">
		<div class='modal-header'>
			<h3 class='modal-title'><?php echo Yii::t("app/admin", "Create new option"); ?></h3>
		</div>
		<div class='modal-body'>
			<label><?php echo Yii::t("app/admin", "Select country"); ?></label>
			<div
				angular-multi-select
				id-property="country_code"
				input-model="countries"
				output-model="selected_country"
				tick-property="checked"
				item-label="{{ countries_lookup[country_code] }}"
				selection-mode="single"
				button-template="angular-multi-select-btn-data.htm"
				button-label="{{ countries_lookup[country_code] }}"
				search-property="country_name['<?php echo array_keys(Lang::EN_US)[0]; ?>']"
				helper-elements="noall nonone noreset filter">
			</div>
		</div>
		<div class='modal-footer'>
			<button class='btn btn-success' ng-click='form.$submitted = true; form.$valid && ok()'><?php echo Yii::t("app/admin", "Confirm"); ?></button>
			<button class='btn btn-primary' ng-click='cancel()' type="submit"><?php echo Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>

<script type="text/ng-template" id="template/modal/sizechart/create_new_column.html">
	<form novalidate name="form">
		<div class='modal-header'>
			<h3 class='modal-title'><?php echo Yii::t("app/admin", "Name of column"); ?></h3>
		</div>
		<div class='modal-body'>
			<label><?php echo Yii::t("app/admin", "Title"); ?></label>
			<div class="input-group" ng-repeat="(lang_k, lang_v) in data.langs">
				<span class="input-group-addon" id="basic-addon-{{ $index }}">{{ lang_v }}</span>
				<input required="" type="text" class="form-control" placeholder="<?php echo Yii::t("app/admin", "Option name..."); ?>"
					aria-describedby="basic-addon-{{ $index }}" ng-model="data.column[lang_k]" name="{{ lang_k }}">
				<span class="input-group-addon alert-danger" ng-show="form.$submitted && !form.$valid && !form['{{lang_k}}'].$valid">
					<span ng-show="form['{{lang_k}}'].$error.required"><?php echo Yii::t("app/admin", "Required!"); ?></span>
				</span>
			</div>
		</div>
		<div class='modal-footer'>
			<button class='btn btn-success' ng-click='form.$submitted = true; form.$valid && ok()'><?php echo Yii::t("app/admin", "Create column"); ?></button>
			<button class='btn btn-primary' ng-click='cancel()' type="submit"><?php echo Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>
