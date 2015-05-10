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
<div class="site-index" ng-app="todevise">

	<div class="body-content" ng-controller="categoriesCtrl">

		<div class="row">
			<div class="col-sm-12 col-md-12 col-lg-12">
				<h2>Categories</h2>
				<div>
					Search:
					<input ng-model="searchModel" ng-change="search()" class="" placeholder="Category name..."></input>
				</div>

				<button ng-click="open_all()">Open all</button>
				<button ng-click="close_all()">Close all</button>
				<button ng-click="create()">Create root category</button>

				<div js-tree="treeConfig" ng-model="treeData" should-apply="ignoreModelChanges()" tree="treeInstance"
					tree-events="ready:readyCB;create_node:restoreState;move_node:move">
				</div>
			</div>
		</div>

	</div>

</div>
