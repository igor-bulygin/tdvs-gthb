<?php
use app\assets\desktop\admin\TermsAsset;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Terms',
	'url' => ['/admin/terms']
];

TermsAsset::register($this);

$this->title = 'Todevise / Admin / Terms';
?>

<div class="row no-gutter" ng-controller="termsCtrl as termsCtrl">
	<div class="col-xs-12 no-horizontal-padding">
		<div class="row no-gutter page-title-row bgcolor-3d">
			<div class="row-same-height">
				<div class="col-xs-3 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071"><?= Yii::t("app/admin", "Categories"); ?></h2>
				</div>

				<div class="col-xs-5 col-height col-middle">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Search</span>
						<input type="text" class="form-control" placeholder="<?= Yii::t("app/admin", "Category name..."); ?>" aria-describedby="basic-addon1" ng-model="termsCtrl.searchModel" ng-change="termsCtrl.search()">
					</div>
				</div>

				<div class="col-xs-4 col-height col-middle text-center">
					<button class="btn btn-light-green fc-fff funiv fs0-786 fs-upper margin-l0-r1" ng-click="termsCtrl.open_all()"><?= Yii::t("app/admin", "Open all"); ?></button>
					<button class="btn btn-coral fc-fff funiv fs0-786 fs-upper margin-l0-r1" ng-click="termsCtrl.close_all()"><?= Yii::t("app/admin", "Close all"); ?></button>
					<button class="btn btn-purple fc-fff funiv fs0-786 fs-upper" ng-click="termsCtrl.create()"><?= Yii::t("app/admin", "Create new category"); ?></button>
				</div>
			</div>
		</div>

		<div js-tree="termsCtrl.treeConfig" ng-model="termsCtrl.treeData" tree="termsCtrl.treeInstance"
			tree-events="ready:termsCtrl.readyCB;create_node:termsCtrl.restoreState;move_node:termsCtrl.move">
		</div>

	</div>
</div>

<script type="text/ng-template" id="template/modal/terms/create_new.html">
	<form novalidate name="create_newCtrl.form" class="popup-tag">
		<div class='modal-header'>
			<h3 class='modal-title funiv fs1'><?= Yii::t("app/admin", "Create Term Group"); ?></h3>
		</div>
		<div class='modal-body'>
			<label class="modal-title funiv fs1 fnormal fc-18"><?= Yii::t("app/admin", "Group name"); ?></label>
			<div class="input-group" ng-repeat="(lang_k, lang_v) in create_newCtrl.data.langs">
				<span class="input-group-addon funiv_bold fs1 fs-upper" id="basic-addon-{{ $index }}">{{ lang_v }}</span>
				<input required="" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Group name..."); ?>" aria-describedby="basic-addon-{{ $index }}" ng-model="create_newCtrl.langs[lang_k]" name="{{ lang_k }}">
				<span class="input-group-addon alert-danger funiv fs0-929" ng-show="create_newCtrl.form.$submitted && !create_newCtrl.form.$valid && !create_newCtrl.form['{{lang_k}}'].$valid">
					<span ng-show="create_newCtrl.form['{{lang_k}}'].$error.required"><?= Yii::t("app/admin", "Required!"); ?></span>
				</span>
			</div>

		</div>
		<div class='modal-footer'>
			<button class='btn btn-light-green fc-18 funiv fs0-786 fs-upper' ng-click='create_newCtrl.form.$submitted = true; create_newCtrl.form.$valid && create_newCtrl.ok()'><?= Yii::t("app/admin", "Confirm"); ?></button>
			<button class='btn btn-grey fc-fff funiv fs-upper fs0-786' ng-click='create_newCtrl.cancel()' type="submit"><?= Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>

<script type="text/ng-template" id="template/modal/terms/edit.html">
	<form novalidate name="editCtrl.form" class="popup-tag">
		<div class='modal-header'>
			<h3 class='modal-title funiv fs1'><?= Yii::t("app/admin", "Edit category"); ?></h3>
		</div>
		<div class='modal-body'>
			<label class="modal-title funiv fs1 fnormal fc-18"><?= Yii::t("app/admin", "Category name"); ?></label>
			<div class="input-group" ng-repeat="(lang_k, lang_v) in editCtrl.data.langs">
				<span class="input-group-addon funiv_bold fs1 fs-upper" id="basic-addon-{{ $index }}">{{ lang_v }}</span>
				<input required="" type="text" class="form-control funiv fs1" placeholder="<?= Yii::t("app/admin", "Category name..."); ?>" aria-describedby="basic-addon-{{ $index }}" ng-model="editCtrl.data.category.name[lang_k]" name="{{ lang_k }}">
				<span class="input-group-addon alert-danger funiv fs0-929" ng-show="editCtrl.form.$submitted && !editCtrl.form.$valid && !editCtrl.form['{{lang_k}}'].$valid">
					<span ng-show="editCtrl.form['{{lang_k}}'].$error.required"><?= Yii::t("app/admin", "Required!"); ?></span>
				</span>
			</div>

		</div>
		<div class='modal-footer'>
			<button class='btn btn-light-green fc-18 funiv fs0-786 fs-upper' ng-click='editCtrl.form.$submitted = true; editCtrl.form.$valid && editCtrl.ok()'><?= Yii::t("app/admin", "Confirm"); ?></button>
			<button class='btn btn-grey fc-fff funiv fs-upper fs0-786' ng-click='editCtrl.cancel()' type="submit"><?= Yii::t("app/admin", "Cancel"); ?></button>
		</div>
	</form>
</script>
