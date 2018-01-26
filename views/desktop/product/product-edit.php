<?php

use app\assets\desktop\product\GlobalAsset;
use app\assets\desktop\pub\Product2Asset;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var \app\models\Person $person */
/** @var \app\models\Product $product */

$this->title = Yii::t('app/public',
	'EDIT_PRODUCT_BY_PERSON_NAME',
	['product_name' => $product->name, 'person_name' => $person->getName()]
);
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');
$this->registerJs("var product = ".Json::encode($product), yii\web\View::POS_HEAD, 'product-var-script');

?>
<div ng-controller="editProductCtrl as editProductCtrl">
	<div id="newProductHeader" class="new-product-header ng-class:{'purple': editProductCtrl.errors}" data-spy="affix" data-offset-top="120">
		<div class="container relative">
			<div class="pull-left">
				<a class="back-link" ng-href="{{editProductCtrl.link_profile}}"><span translate="product.creation_edition.BACK_TO_PROFILE"></span></a>
			</div>
			<div class="avatar">
				<img ng-src="{{editProductCtrl.profile}}">
			</div>
			<div class="text-center" ng-if="!editProductCtrl.errors"><h4 class="title"><span translate="product.creation_edition.EDIT_WORK"></span></h4></div>
			<div class="text-center" ng-if="editProductCtrl.errors" ng-cloak><p><span translate="product.creation_edition.COMPLETE_REQUIRED_FIELDS"></span></p></div>
			<div class="btns-group">
				<button class="btn btn-transparent" ng-click="editProductCtrl.save()" ng-disabled="editProductCtrl.saving"><span translate="product.creation_edition.SAVE_PROGRESS"></span></button>
				<button class="btn btn-default btn-red" ng-click="editProductCtrl.save('true')" ng-disabled="editProductCtrl.saving"><span translate="product.creation_edition.PUBLISH_WORK"></span></button>
			</div>
		</div>
	</div>
	<div class="create-work-wrapper">
		<div id="productSaved" class="success-message-120" ng-if="editProductCtrl.progressSaved" ng-cloak><p class="text-center"><span translate="product.creation_edition.PRODUCT_SAVED"></span></p></div>
		<div class="container" >
			<div ng-show="!editProductCtrl.saving" ng-if ="editProductCtrl.product" ng-cloak>
				<product-basic-info ng-if="editProductCtrl.product.variationsLoaded" product="editProductCtrl.product" categories="editProductCtrl.allCategories" languages="editProductCtrl.languages"></product-basic-info>
				<product-variations  product="editProductCtrl.product" categories="editProductCtrl.allCategories" languages="editProductCtrl.languages" tags="editProductCtrl.tags" sizecharts="editProductCtrl.sizecharts" metric="editProductCtrl.metric" deviser="editProductCtrl.deviser" papertypes="editProductCtrl.papertypes" fromedit="editProductCtrl.from_edit"></product-variations>
				<product-price-stock ng-if="editProductCtrl.product.variationsLoaded" product="editProductCtrl.product" categories="editProductCtrl.allCategories" tags="editProductCtrl.tags" papertypes="editProductCtrl.papertypes" metric="editProductCtrl.metric" fromedit="editProductCtrl.from_edit"></product-price-stock>
				<product-more-details product="editProductCtrl.product" languages="editProductCtrl.languages"></product-more-details>
				<div class="text-center">
					<button class="btn btn-default btn-red" ng-click="editProductCtrl.save('true')" ng-disabled="createProductCtrl.saving"><span translate="product.creation_edition.PUBLISH_WORK"></span></button>
				</div>
			</div>
			<div class="mt-20 tdvs-loading" ng-if="editProductCtrl.saving || !editProductCtrl.product">
				<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
				<span class="sr-only" translate="product.creation_edition.LOADING"></span>
			</div>
		</div>
	</div>
</div>
