<?php
use app\assets\desktop\admin\FaqsAsset;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Faqs',
	'url' => ['/admin/faqs']
];

FaqsAsset::register($this);

$this->title = 'Todevise / Admin / Faqs';
?>

<div class="row no-gutter" ng-controller="faqsCtrl">
	<div class="col-xs-12 no-horizontal-padding">
		<div class="row no-gutter page-title-row bgcolor-3d">
			<div class="row-same-height">
				<div class="col-xs-3 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Categories"); ?></h2>
				</div>

				<div class="col-xs-5 col-height col-middle">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Search</span>
						<input type="text" class="form-control" placeholder="<?= Yii::t("app/admin", "Category name..."); ?>" aria-describedby="basic-addon1" ng-model="searchModel" ng-change="search()">
					</div>
				</div>

				<div class="col-xs-4 col-height col-middle text-center">
					<button class="btn btn-light-green fc-fff funiv fs0-786 fs-upper margin-l0-r1" ng-click="open_all()"><?= Yii::t("app/admin", "Open all"); ?></button>
					<button class="btn btn-coral fc-fff funiv fs0-786 fs-upper margin-l0-r1" ng-click="close_all()"><?= Yii::t("app/admin", "Close all"); ?></button>
					<button class="btn btn-purple fc-fff funiv fs0-786 fs-upper" ng-click="create()"><?= Yii::t("app/admin", "Create new category"); ?></button>
				</div>
			</div>
		</div>

		<div js-tree="treeConfig" ng-model="treeData" tree="treeInstance"
			tree-events="ready:readyCB;create_node:restoreState;move_node:move">
		</div>

	</div>
</div>

<script type="text/ng-template" id="template/modal/faqs/create_new.html">
	<form novalidate name="form" class="popup-tag">
		<div class='modal-header'>
			<h3 class='modal-title funiv fs1'><?= Yii::t("app/admin", "Create FAQ Group"); ?></h3>
		</div>
		<div class='modal-body'>
			<label class="modal-title funiv fs1 fnormal fc-18"><?= Yii::t("app/admin", "Group name"); ?></label>
			<div class="input-group" ng-repeat="(lang_k, lang_v) in data.langs">
				<span class="input-group-addon funiv_bold fs1 fs-upper" id="basic-addon-{{ $index }}">{{ lang_v }}</span>
				<input required="" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Group name..."); ?>" aria-describedby="basic-addon-{{ $index }}" ng-model="langs[lang_k]" name="{{ lang_k }}">
				<span class="input-group-addon alert-danger funiv fs0-929" ng-show="form.$submitted && !form.$valid && !form['{{lang_k}}'].$valid">
					<span ng-show="form['{{lang_k}}'].$error.required"><?= Yii::t("app/admin", "Required!"); ?></span>
				</span>
			</div>

		</div>
		<div class='modal-footer'>
			<button class='btn btn-light-green fc-18 funiv fs0-786 fs-upper' ng-click='form.$submitted = true; form.$valid && ok()'><?= Yii::t("app/admin", "Confirm"); ?></button>
			<button class='btn btn-grey fc-fff funiv fs-upper fs0-786' ng-click='cancel()' type="submit"><?= Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>

<script type="text/ng-template" id="template/modal/faqs/edit.html">
	<form novalidate name="form" class="popup-tag">
		<div class='modal-header'>
			<h3 class='modal-title funiv fs1'><?= Yii::t("app/admin", "Edit category"); ?></h3>
		</div>
		<div class='modal-body'>
			<label class="modal-title funiv fs1 fnormal fc-18"><?= Yii::t("app/admin", "Category name"); ?></label>
			<div class="input-group" ng-repeat="(lang_k, lang_v) in data.langs">
				<span class="input-group-addon funiv_bold fs1 fs-upper" id="basic-addon-{{ $index }}">{{ lang_v }}</span>
				<input required="" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Category name..."); ?>" aria-describedby="basic-addon-{{ $index }}" ng-model="data.category.name[lang_k]" name="{{ lang_k }}">
				<span class="input-group-addon alert-danger funiv fs0-929" ng-show="form.$submitted && !form.$valid && !form['{{lang_k}}'].$valid">
					<span ng-show="form['{{lang_k}}'].$error.required"><?= Yii::t("app/admin", "Required!"); ?></span>
				</span>
			</div>

		</div>
		<div class='modal-footer'>
			<button class='btn btn-light-green fc-18 funiv fs0-786 fs-upper' ng-click='form.$submitted = true; form.$valid && ok()'><?= Yii::t("app/admin", "Confirm"); ?></button>
			<button class='btn btn-grey fc-fff funiv fs-upper fs0-786' ng-click='cancel()' type="submit"><?= Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>
