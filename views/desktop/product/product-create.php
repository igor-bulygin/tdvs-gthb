<?php
use app\assets\desktop\product\GlobalAsset;
use app\models\Person;
use app\models\PersonVideo;
use app\models\Product;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $deviser */
/** @var Product $product */
/** @var PersonVideo $video */

$this->title = $person->getName() . ' - Todevise';
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>
<div ng-controller="createProductCtrl as createProductCtrl">
	<div id="newProductHeader" class="new-product-header ng-class:{'purple': createProductCtrl.errors}" data-spy="affix" data-offset-top="120" ng-if="createProductCtrl.isPublicProfile">
		<div class="container relative">
			<div class="pull-left">
				<a class="back-link" ng-href="{{createProductCtrl.link_profile}}"> &lt go back to your profile</a>
			</div>
			<div class="avatar">
				<img ng-src="{{createProductCtrl.profile || '/imgs/default-avatar.png'}}">
			</div>
			<div class="text-center" ng-if="!createProductCtrl.errors"><h4 class="title">New work</h4></div>
			<div class="text-center" ng-if="createProductCtrl.errors" ng-cloak><p>Please complete all the required fields before publishing your work.</p></div>
			<div class="btns-group">
				<button class="btn btn-transparent" ng-click="createProductCtrl.save('product_state_draft')" ng-disabled="createProductCtrl.saving">Save progress</button>
				<button class="btn btn-default btn-green" ng-click="createProductCtrl.save('product_state_active')" ng-disabled="createProductCtrl.saving">Publish work</button>
			</div>
		</div>
	</div>
	<div class="create-work-wrapper" >
		<div id="productSaved" class="success-message-120" ng-if="createProductCtrl.progressSaved" ng-cloak><p class="text-center">Product saved</p></div>
		<div class="container">
			<div ng-if="!createProductCtrl.saving">
				<product-basic-info emptyCategory="createProductCtrl.emptyCategory" product="createProductCtrl.product" categories="createProductCtrl.allCategories" languages="createProductCtrl.languages"></product-basic-info>
				<product-variations product="createProductCtrl.product" categories="createProductCtrl.allCategories" languages="createProductCtrl.languages" tags="createProductCtrl.tags" sizecharts="createProductCtrl.sizecharts" metric="createProductCtrl.metric" deviser="createProductCtrl.deviser" papertypes="createProductCtrl.papertypes"></product-variations>
				<product-price-stock product="createProductCtrl.product" categories="createProductCtrl.allCategories" tags="createProductCtrl.tags" papertypes="createProductCtrl.papertypes" metric="createProductCtrl.metric"></product-price-stock>
				<product-more-details product="createProductCtrl.product" languages="createProductCtrl.languages"></product-more-details>
				<div class="text-center">
					<button class="btn btn-default btn-green" ng-click="createProductCtrl.save('product_state_active')" ng-disabled="createProductCtrl.saving">Publish work</button>
				</div>
			</div>
			<div class="text-center" ng-if="createProductCtrl.saving">
				<img src="/imgs/loading.gif">
			</div>
		</div>
	</div>
	
</div>
