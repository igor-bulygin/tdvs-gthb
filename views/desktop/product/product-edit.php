<?php
use app\assets\desktop\product\GlobalAsset;
use app\assets\desktop\pub\Product2Asset;
use yii\helpers\Json;

GlobalAsset::register($this);

$this->title = $person->getName() . ' - Todevise';
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');
$this->registerJs("var product = ".Json::encode($product), yii\web\View::POS_HEAD, 'product-var-script');

?>
<div ng-controller="editProductCtrl as editProductCtrl">
	<div id="newProductHeader" class="new-product-header ng-class:{'purple': editProductCtrl.errors}" data-spy="affix" data-offset-top="120">
		<div class="container relative">
			<div class="pull-left">
				<a class="back-link" ng-href="{{editProductCtrl.link_profile}}"> &lt go back to your profile</a>
			</div>
			<div class="avatar">
				<img ng-src="{{editProductCtrl.profile}}">
			</div>
			<div class="text-center" ng-if="!editProductCtrl.errors"><h4 class="title">Edit work</h4></div>
			<div class="text-center" ng-if="editProductCtrl.errors" ng-cloak><p>Please complete all the required fields before publishing your work.</p></div>
			<div class="btns-group">
				<button class="btn btn-transparent" ng-click="editProductCtrl.save()" ng-disabled="editProductCtrl.saving">Save progress</button>
				<button class="btn btn-default btn-green" ng-click="editProductCtrl.save('true')" ng-disabled="editProductCtrl.saving">Publish work</button>
			</div>
		</div>
	</div>
	<div class="create-work-wrapper">
		<div id="productSaved" class="success-message-120" ng-if="editProductCtrl.progressSaved" ng-cloak><p class="text-center">Product saved</p></div>
		<div class="container" >
			<div ng-show="!editProductCtrl.saving">
				<product-basic-info product="editProductCtrl.product" categories="editProductCtrl.allCategories" languages="editProductCtrl.languages"></product-basic-info>
				<product-variations product="editProductCtrl.product" categories="editProductCtrl.allCategories" languages="editProductCtrl.languages" tags="editProductCtrl.tags" sizecharts="editProductCtrl.sizecharts" metric="editProductCtrl.metric" deviser="editProductCtrl.deviser" papertypes="editProductCtrl.papertypes" fromedit="editProductCtrl.from_edit"></product-variations>
				<product-price-stock product="editProductCtrl.product" categories="editProductCtrl.allCategories" tags="editProductCtrl.tags" papertypes="editProductCtrl.papertypes" metric="editProductCtrl.metric" fromedit="editProductCtrl.from_edit"></product-price-stock>
				<product-more-details product="editProductCtrl.product" languages="editProductCtrl.languages"></product-more-details>
				<div class="text-center">
					<button class="btn btn-default btn-green" ng-click="editProductCtrl.save('true')" ng-disabled="createProductCtrl.saving">Publish work</button>
				</div>
			</div>
			<div class="text-center" ng-if="editProductCtrl.saving">
				<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
				<span class="sr-only">Loading...</span>
			</div>
		</div>
	</div>
</div>