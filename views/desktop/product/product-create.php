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
use app\assets\desktop\product\createProductAsset;

createProductAsset::register($this);

/** @var Person $deviser */
/** @var Product $product */
/** @var PersonVideo $video */

$this->title = $deviser->getBrandName() . ' - Todevise';

?>
<div ng-controller="createProductCtrl as createProductCtrl" class="create-work-wrapper">
	<div id="newProductHeader" style="background-color: black; height: 50px;">
		<div class="col-sm-2"><a href="">< go back to your profile</a></div>
		<div class="col-sm-8 text-center"><h4>New work</h4></div>
		<div class="col-md-2">
			<button class="btn btn-default">Save progress</button>
			<button class="btn btn-default btn-green">Publish work</button>
		</div>
	</div>
	<div class="container">
		<product-basic-info product="createProductCtrl.product" categories="createProductCtrl.allCategories" languages="createProductCtrl.languages"></product-basic-info>
		<product-more-details product="createProductCtrl.product" languages="createProductCtrl.languages"></product-more-details>
		<product-variations product="createProductCtrl.product"></product-variations>
		<product-price-stock product="createProductCtrl.product"></product-price-stock>
	</div>
</div>