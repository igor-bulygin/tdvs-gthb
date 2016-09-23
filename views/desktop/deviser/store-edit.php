<?php
use app\components\DeviserHeader;
use app\components\DeviserMenu;
use app\models\Category;
use app\models\Person;
use app\models\Product;
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use app\assets\desktop\pub\Index2Asset;
use app\assets\desktop\deviser\EditStoreAsset;

EditStoreAsset::register($this);

/** @var Person $deviser */
/** @var Product $product */
/** @var Category $category */
/** @var Category $selectedCategory */

$this->title = $deviser->personalInfo->getBrandName() . ' - Todevise';

// use params to share data between views :(
$this->params['deviser_menu_categories'] = $categories;
$this->params['deviser_menu_active_option'] = 'store';
$this->params['deviser'] = $deviser;

?>

	<?= DeviserHeader::widget() ?>

		<div class="store">
			<div class="container">
				<div class="row">
					<div class="col-md-2">
						<?= DeviserMenu::widget() ?>
					</div>
					<div class="col-md-10" ng-controller="editStoreCtrl as editStoreCtrl">
						<div class="content-store">
							<div class="store-grid">
								<div class="title-wrapper">
									<span class="title">category</span>
									<div class="mesonry-row" dnd-list="editStoreCtrl.products">
										<div class="menu-category list-group" ng-repeat="product in editStoreCtrl.products" ng-if="product.main_photo" dnd-draggable="product" dnd-effect-allowed="move" dnd-moved="editStoreCtrl.update($index, product)">
											<a href="">
												<div class="grid">
													<figure class="effect-zoe">
														<img class="grid-image" ng-src="{{product.main_photo}}">
														<figcaption>
															<p class="instauser">{{product.name}}</p>
															<p class="price">€ 0</p>
														</figcaption>
													</figure>
												</div>
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>