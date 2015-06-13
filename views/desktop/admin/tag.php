<?php
use yii\web\View;
use yii\helpers\Json;
use app\assets\desktop\admin\TagAsset;

/* @var $this yii\web\View */
/* @var $categories ArrayObject */
/* @var $tags yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = [
	'label' => 'Tag',
	'url' => ['/admin/tag']
];

TagAsset::register($this);

$this->title = 'Todevise / Admin / Tag';
?>

<div class="row" ng-controller="tagCtrl" ng-init="init()">
	<div class="col-sm-12 col-md-12 col-lg-12">

		<?php $this->registerJs("var _categories = " . Json::encode($categories) . ";", View::POS_HEAD) ?>
		<?php $this->registerJs("var _tag = " . Json::encode($tag) . ";", View::POS_HEAD) ?>

		<div class="row">
			<div class="col-md4 col-lg-4">
				<h2><?= Yii::t("app/admin", "Tag list"); ?></h2>
			</div>
			<div class="col-md8 col-lg-8">
				<button class="btn btn-default" ng-click="cancel()">Cancel changes</button>
				<button class="btn btn-default" ng-click="save()">Save changes</button>
			</div>
		</div>

		<div class="row">
			<div class="col-md4 col-lg-4">
				<span>Tag name</span>
				<input type="text" class="form-control" placeholder="" ng-model="tag.name[lang]">
			</div>
			<div class="col-md4 col-lg-4">
				<span>Description</span>
				<input type="text" class="form-control" placeholder="" ng-model="tag.description[lang]">
			</div>
			<div class="col-md4 col-lg-4">
				<span>Categories</span>
				<div
					angular-multi-select
					input-model="categories"
					output-model="selectedCategories"

					group-property="sub"
					tick-property="check"

					item-label="{{ name[lang] }}"
					selection-mode="multi"
					search-property="name[lang]"
					min-search-length="3"
					hidden-property="hidden"
					helper-elements="noall nonone noreset nofilter"
					></div>
			</div>
		</div>

	</div>
</div>