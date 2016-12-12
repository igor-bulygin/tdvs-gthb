<?php
use app\assets\desktop\pub\Product2Asset;
use app\assets\desktop\pub\ProductDetailAsset;
use app\assets\desktop\pub\PublicCommonAsset;
use app\models\Category;
use app\models\Person;
use app\models\PersonVideo;
use app\models\Product;
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use app\assets\desktop\product\editProductAsset;

editProductAsset::register($this);

/** change this */
/** @var Person $deviser */
/** @var Product $product */
/** @var PersonVideo $video */

$this->title = $deviser->getBrandName() . ' - Todevise';

?>

<div ng-controller="editProductCtrl as editProductCtrl" class="create-work-wrapper">
	<div id="newProductHeader" class="new-product-header">
		<div class="pull-left">
			<a class="back-link" ng-href="{{editProductCtrl.link_profile}}"> &lt go back to your profile</a>
		</div>
		<div class="avatar">
			<img ng-src="{{editProductCtrl.profile}}">
		</div>
		<div class="text-center"><h4 class="title">Mockup</h4></div>
		<div class="btns-group">
			<button class="btn btn-transparent" ng-click="editProductCtrl.save()">Save progress</button>
			<button class="btn btn-default btn-green" disabled>Publish work</button>
		</div>
	</div>
	<div class="container">
		<product-basic-info product="editProductCtrl.product" categories="editProductCtrl.allCategories" languages="editProductCtrl.languages"></product-basic-info>
		<product-more-details product="editProductCtrl.product" languages="editProductCtrl.languages"></product-more-details>
		<product-variations product="editProductCtrl.product" categories="editProductCtrl.allCategories" languages="editProductCtrl.languages" tags="editProductCtrl.tags" sizecharts="editProductCtrl.sizecharts" metric="editProductCtrl.metric" deviser="editProductCtrl.deviser" papertypes="editProductCtrl.papertypes"></product-variations>
	</div>
</div>