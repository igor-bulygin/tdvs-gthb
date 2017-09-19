<?php

use app\assets\desktop\product\GlobalAsset;
use app\models\Person;
use app\models\PersonVideo;
use app\models\Product;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */
/** @var Product $product */
/** @var PersonVideo $video */

$this->title = Yii::t('app/public',
	'CREATE_NEW_WORK_BY_PERSON_NAME',
	['person_name' => $person->getName()]
);
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>
<div ng-controller="createProductCtrl as createProductCtrl">
	<div id="newProductHeader" class="new-product-header ng-class:{'purple': createProductCtrl.errors}" data-spy="affix" data-offset-top="120" ng-if="createProductCtrl.isPublicProfile">
		<div class="container relative">
			<div class="pull-left">
				<a class="back-link" ng-href="{{createProductCtrl.link_profile}}"><span translate="product.creation_edition.BACK_TO_PROFILE"></span></a>
			</div>
			<div class="avatar">
				<img ng-src="{{createProductCtrl.profile || '/imgs/default-avatar.png'}}">
			</div>
			<div class="text-center" ng-if="!createProductCtrl.errors"><h4 class="title"><span translate="product.creation_edition.NEW_WORK"></span></h4></div>
			<div class="text-center" ng-if="createProductCtrl.errors" ng-cloak><p><span translate="product.creation_edition.COMPLETE_REQUIRED_FIELDS"></span></p></div>
			<div class="btns-group">
				<button class="btn btn-transparent" ng-click="createProductCtrl.save('product_state_draft')" ng-disabled="createProductCtrl.saving"><span translate="product.creation_edition.SAVE_PROGRESS"></span></button>
				<button class="btn btn-default btn-green" ng-click="createProductCtrl.save('product_state_active')" ng-disabled="createProductCtrl.saving"><span translate="product.creation_edition.PUBLISH_WORK"></span></button>
			</div>
		</div>
	</div>
	<div class="create-work-wrapper" >
		<div id="productSaved" class="success-message-120" ng-if="createProductCtrl.progressSaved" ng-cloak><p class="text-center"><span translate="product.creation_edition.PRODUCT_SAVED"></span></p></div>
		<div class="container" >
			<div ng-show="!createProductCtrl.saving">
				<product-basic-info product="createProductCtrl.product" categories="createProductCtrl.allCategories" languages="createProductCtrl.languages"></product-basic-info>
				<product-variations product="createProductCtrl.product" categories="createProductCtrl.allCategories" languages="createProductCtrl.languages" tags="createProductCtrl.tags" sizecharts="createProductCtrl.sizecharts" metric="createProductCtrl.metric" deviser="createProductCtrl.deviser" papertypes="createProductCtrl.papertypes"></product-variations>
				<product-price-stock product="createProductCtrl.product" categories="createProductCtrl.allCategories" tags="createProductCtrl.tags" papertypes="createProductCtrl.papertypes" metric="createProductCtrl.metric"></product-price-stock>
				<product-more-details product="createProductCtrl.product" languages="createProductCtrl.languages"></product-more-details>
				<div class="text-center">
					<button class="btn btn-red btn-big btn-red" ng-click="createProductCtrl.save('product_state_active')" ng-disabled="createProductCtrl.saving"><span translate="product.creation_edition.PUBLISH_WORK"></span></button>
				</div>
			</div>
			<div class="text-center" ng-if="createProductCtrl.saving">
				<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
				<span class="sr-only" translate="product.creation_edition.LOADING"></span>
			</div>
		</div>
	</div>
</div>
