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
	<div class="col-sm-12 col-md-12 col-lg-12">
		<h2>Categories</h2>
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon1">Search</span>
			<input type="text" class="form-control" placeholder="Category name..." aria-describedby="basic-addon1" ng-model="searchModel" ng-change="search()">
		</div>

		<button class="btn btn-default" ng-click="open_all()">Open all</button>
		<button class="btn btn-default" ng-click="close_all()">Close all</button>
		<button class="btn btn-default" ng-click="create()">Create root category</button>

		<div js-tree="treeConfig" ng-model="treeData" should-apply="ignoreModelChanges" tree="treeInstance"
			tree-events="ready:readyCB;create_node:restoreState;move_node:move">
		</div>
	</div>
</div>
