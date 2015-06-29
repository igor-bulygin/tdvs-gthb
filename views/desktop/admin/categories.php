<?php
use app\assets\desktop\admin\CategoriesAsset;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Categories',
	'url' => ['/admin/categories']
];

CategoriesAsset::register($this);

$this->title = 'Todevise / Admin / Categories';
?>

<div class="row no-gutter" ng-controller="categoriesCtrl">
	<div class="col-xs-12 no-horizontal-padding">

		<div class="row no-gutter page-title-row bgcolor-3d">
			<div class="row-same-height">
				<div class="col-xs-3 col-height col-middle">
					<h2 class="page-title funiv_bold fs-upper fc-fff fs1-071">Categories</h2>
				</div>

				<div class="col-xs-5 col-height col-middle">
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">Search</span>
						<input type="text" class="form-control" placeholder="Category name..." aria-describedby="basic-addon1" ng-model="searchModel" ng-change="search()">
					</div>
				</div>

				<div class="col-xs-4 col-height col-middle text-center">
					<button class="btn btn-light-green fc-fff funiv fs0-786 fs-upper margin-l0-r1" ng-click="open_all()">Open all</button>
					<button class="btn btn-coral fc-fff funiv fs0-786 fs-upper margin-l0-r1" ng-click="close_all()">Close all</button>
					<button class="btn btn-purple fc-fff funiv fs0-786 fs-upper" ng-click="create()">Create root category</button>
				</div>
			</div>
		</div>

		<div js-tree="treeConfig" ng-model="treeData" should-apply="ignoreModelChanges" tree="treeInstance"
			tree-events="ready:readyCB;create_node:restoreState;move_node:move">
		</div>

	</div>
</div>
