<?php

use app\assets\desktop\admin\SizeChartAsset;
use yii\helpers\Json;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $countries ArrayObject */
/* @var $sizechart ArrayObject */
/* @var $categories ArrayObject */
/* @var $countries_lookup ArrayObject */

$this->params['breadcrumbs'][] = [
	'label' => 'Size chart',
	'url' => ['/admin/size-chart']
];

SizeChartAsset::register($this);

$this->title = 'Todevise / Admin / Size chart';
?>

	<div class="row no-gutter" ng-controller="sizeChartCtrl as sizeChartCtrl">
		<div class="col-xs-12 no-horizontal-padding">

			<?php $this->registerJs("var _categories = " . Json::encode($categories) . ";", View::POS_HEAD); ?>
				<?php $this->registerJs("var _sizechart = " . Json::encode($sizechart) . ";", View::POS_HEAD); ?>
					<?php $this->registerJs("var _countries_lookup = " . Json::encode($countries_lookup) . ";", View::POS_HEAD); ?>

						<div class="row no-gutter page-title-row bgcolor-3d">
							<div class="row-same-height">
								<div class="col-xs-4 col-height col-middle">
									<h6 class="page-title funiv_bold fs-upper fc-fff 0-857"><?= Yii::t("app/admin", "Create size chart"); ?></h6>
								</div>
								<div class="col-md-8 col-lg-8 col-height col-middle">
									<div class="pull-right">
										<button class="btn btn btn-grey fc-fff funiv fs-upper fs0-786" ng-click="sizeChartCtrl.cancel()">
											<?= Yii::t("app/admin", "Cancel changes"); ?>
										</button>
										<button class="btn btn-light-green fc-18 funiv fs-upper fs0-786" ng-click="sizeChartCtrl.save()">
											<?= Yii::t("app/admin", "Save changes"); ?>
										</button>
									</div>
								</div>
							</div>
						</div>

						<div class="row no-gutter flex flex-row">
							<div class="col-xs-4 div-bordered size-chart-settings">
								<div class="checkbox padding-1">
									<label class="funiv fc-c7 fs0-929">
										<input type="checkbox" ng-model="sizeChartCtrl.sizechart.enabled">
										<?= Yii::t("app/admin", "Enabled"); ?>
									</label>
								</div>
							</div>
							<div class="col-xs-4 div-bordered size-chart-settings">
								<div class="padding-1">
									<label class="funiv fc-c7 fs0-929 fnormal">
										<?= Yii::t("app/admin", "Categories"); ?>
									</label>
									<div angular-multi-select input-model="sizeChartCtrl.categories" output-model="sizeChartCtrl.sizechart.categories" output-keys="short_id" output-type="values" id-property="short_id" checked-property="check" children-property="sub" dropdown-label="<[ '<(name)>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/public', 'SELECT_CATEGORY') ?>']>" node-label="<[ name ]>" leaf-label="<[ name ]>" preselect="{{ sizechart.categories | arrpatch : 'short_id' }}" search-field="name" hide-helpers="check_all, check_none, reset"></div>
								</div>
							</div>
							<div class="col-xs-4 div-bordered size-chart-settings">
								<div class="padding-1">
									<label class="funiv fc-c7 fs0-929 fnormal">
										<?= Yii::t("app/admin", "Metric unit"); ?>
									</label>
									<div angular-multi-select input-model='<?= Json::encode($mus) ?>' output-model="sizeChartCtrl.sizechart.metric_unit" output-keys="value" name="metric_unit" checked-property="checked" children-property="sub" max-checked-leafs="1" dropdown-label="<[ '<(text)>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/public', 'SELECT_METRIC_UNIT') ?>']>" node-label="<[ text ]>" leaf-label="<[ text ]>" preselect="value, {{ sizeChartCtrl.sizechart.metric_unit }}" hide-helpers="check_all, check_none, reset"></div>
								</div>
							</div>
						</div>

						<br />

						<div class="row-same-height no-gutter">
							<div class="col-xs-1 size-chart-settings col-height col-middle">
								<label class="funiv fc-c7 fs0-929 fnormal">
									<?= Yii::t("app/admin", "Countries"); ?>
								</label>
							</div>
							<div class="col-xs-11 col-height col-middle">
								<div class="row no-gutter sortable-container" sv-root sv-part="sizeChartCtrl.sizechart.countries" sv-on-sort="sizeChartCtrl.move_country($indexFrom, $indexTo)">
									<div ng-cloak class="col-xs-2 country pull-left funiv fc-fff fs1 fs-upper" sv-element ng-repeat="country in sizeChartCtrl.sizechart.countries track by $index">
										<span class="glyphicon glyphicon-menu-hamburger pointer fc-68" sv-handle></span> {{ sizeChartCtrl.countries_lookup[country] }}
										<div class="pull-right">
											<span class="glyphicon glyphicon-trash pointer fc-68" ng-click="sizeChartCtrl.delete_country($index)"></span>
										</div>
									</div>

									<div class="col-xs-1 add-new">
										<a class="pointer fs-upper funiv fc-fff fs0-786" ng-click="sizeChartCtrl.new_country()">
											<?= Yii::t("app/admin", "Add new +"); ?>
										</a>
									</div>
								</div>
							</div>
						</div>

						<div class="div-line"></div>

						<div class="row-same-height no-gutter">
							<div class="col-xs-1 size-chart-settings col-height col-middle">
								<label class="funiv fc-c7 fs0-929 fnormal">
									<?= Yii::t("app/admin", "Columns"); ?>
								</label>
							</div>
							<div class="col-xs-11 col-height col-middle">
								<div class="row no-gutter sortable-container" sv-root sv-part="sizeChartCtrl.sizechart.columns" sv-on-sort="sizeChartCtrl.move_column($indexFrom, $indexTo)">
									<div ng-cloak class="col-xs-2 country pull-left funiv fc-fff fs1" sv-element ng-repeat="column in sizeChartCtrl.sizechart.columns track by $index">
										<span class="glyphicon glyphicon-menu-hamburger pointer fc-68" sv-handle></span> {{ column[sizeChartCtrl.lang] }}
										<div class="pull-right">
											<span class="glyphicon glyphicon-trash pointer fc-68" ng-click="sizeChartCtrl.delete_column($index)"></span>
										</div>
									</div>

									<div class="col-xs-1 add-new">
										<a class="pointer fs-upper funiv fc-fff fs0-786" ng-click="sizeChartCtrl.new_column('Test')">
											<?= Yii::t("app/admin", "Add new +"); ?>
										</a>
									</div>
								</div>
							</div>
						</div>

						<div class="div-line"></div>

						<div class="row-same-height no-gutter" ng-show="sizeChartCtrl.sizechart.values.length > 0">
							<table class="fc-fff fs1-071 fnormal table-size-generated">
								<thead>
									<tr>
										<th class="funiv fc-d6 fs0-857 fnormal text-center" ng-cloak ng-repeat="header in sizeChartCtrl.table_header track by $index">{{ header }}</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-cloak ng-repeat="row in sizeChartCtrl.sizechart.values track by $index">
										<td ng-repeat="cell in sizeChartCtrl.sizechart.values[$index] track by $index">
											<input ng-if="$index < sizeChartCtrl.sizechart.countries.length" ng-model="sizechart.values[$parent.$parent.$index][$index]" />
											<input ng-if="$index >= sizeChartCtrl.sizechart.countries.length" ng-model="sizechart.values[$parent.$parent.$index][$index]" placeholder="0" angular-unit-converter convert-from="{{ sizeChartCtrl.convertFrom }}" convert-to="{{ sizeChartCtrl.convertTo }}" />
										</td>
									</tr>
								</tbody>
							</table>
						</div>

						<br />

						<div class="row no-gutter add-new">
							<a class="pointer fs-upper funiv fc-fff fs0-786" ng-click="sizeChartCtrl.new_row()">
								<?= Yii::t("app/admin", "Add new row +"); ?>
							</a>
						</div>
		</div>
	</div>

	<script type="text/ng-template" id="template/modal/sizechart/create_new_country.html">
		<form novalidate name="create_new_countryCtrl.form" class="popup-country">
			<div class='modal-header'>
				<h3 class='modal-title funiv fs1'><?= Yii::t("app/admin", "Create new option"); ?></h3>
			</div>
			<div class='modal-body'>
				<label class="modal-title funiv fs1 fnormal fc-18">
					<?= Yii::t("app/admin", "Select country"); ?>
				</label>
				<div angular-multi-select input-model='<?= Json::encode($countries) ?>' output-model="[]" name="country" checked-property="checked" children-property="sub" max-checked-leafs="1" dropdown-label="<[ '<(country_name[&quot;{{ create_new_countryCtrl.lang }}&quot;])>' | outputModelIterator : this : ', ' : '<?= Yii::t('app/public', 'SELECT_COUNTRY') ?>']>" node-label="<[ country_name['{{ create_new_countryCtrl.lang }}'] ]>" leaf-label="<[ country_name['{{ create_new_countryCtrl.lang }}'] ]>" hide-helpers="check_all, check_none, reset"></div>
			</div>
			<div class='modal-footer'>
				<button class='btn btn-light-green fc-18 funiv fs0-786 fs-upper' ng-click='create_new_countryCtrl.form.$submitted = true; create_new_countryCtrl.form.$valid && create_new_countryCtrl.ok()'>
					<?= Yii::t("app/admin", "Confirm"); ?>
				</button>
				<button class='btn btn-grey fc-fff funiv fs-upper fs0-786' ng-click='create_new_countryCtrl.cancel()' type="submit">
					<?= Yii::t("app/admin", "Cancel"); ?>
				</button>
			</div>
		</form>
	</script>

	<script type="text/ng-template" id="template/modal/sizechart/create_new_column.html">
		<form novalidate name="create_new_columnCtrl.form" class="popup-size-chart">
			<div class='modal-header'>
				<h3 class='modal-title funiv fs1'><?= Yii::t("app/admin", "Name of column"); ?></h3>
			</div>
			<div class='modal-body'>
				<label class="funiv fs1 fnormal fc-18">
					<?= Yii::t("app/admin", "Title"); ?>
				</label>
				<div class="input-group" ng-repeat="(lang_k, lang_v) in create_new_columnCtrl.data.langs">
					<span class="input-group-addon funiv_bold fs1 fs-upper" id="basic-addon-{{ $index }}">{{ lang_v }}</span>
					<input required="" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Option name... "); ?>" aria-describedby="basic-addon-{{ $index }}" ng-model="create_new_columnCtrl.data.column[lang_k]" name="{{ lang_k }}">
					<span class="input-group-addon alert-danger funiv fs0-929" ng-show="create_new_columnCtrl.form.$submitted && !create_new_columnCtrl.form.$valid && !create_new_columnCtrl.form['{{lang_k}}'].$valid">
					<span ng-show="create_new_columnCtrl.form['{{lang_k}}'].$error.required"><?= Yii::t("app/admin", "Required!"); ?></span>
					</span>
				</div>
			</div>
			<div class='modal-footer'>
				<button class='btn btn-light-green fc-18 funiv fs0-786 fs-upper' ng-click='create_new_columnCtrl.form.$submitted = true; create_new_columnCtrl.form.$valid && create_new_columnCtrl.ok()'>
					<?= Yii::t("app/admin", "Create column"); ?>
				</button>
				<button class='btn btn-grey fc-fff funiv fs-upper fs0-786' ng-click='create_new_columnCtrl.cancel()' type="submit">
					<?= Yii::t("app/admin", "Cancel"); ?>
				</button>
			</div>
		</form>
	</script>