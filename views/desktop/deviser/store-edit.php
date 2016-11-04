<?php
use app\components\DeviserHeader;
use app\components\DeviserAdminHeader;
use app\components\DeviserMakeProfilePublic;
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
$this->params['deviser_links_target'] = 'edit_view';
$this->params['deviser'] = $deviser;

?>

	<?php if ($deviser->isDraft()) { ?>
		<?= DeviserMakeProfilePublic::widget() ?>
	<?php } ?>
	<?= DeviserHeader::widget() ?>

		<div class="store">
			<div class="container">
				<div class="row">
					<div class="col-md-2">
						<?= DeviserMenu::widget() ?>
					</div>
					<div class="col-md-10" ng-controller="editStoreCtrl as editStoreCtrl">
						<div class="content-store">
							<?php if (count($categories) > 1) { ?>
								<div class="cathegory-wrapper">
									<?php foreach ($categories as $i => $category) { ?>
										<div class="col-md-3 col-sm-3 col-xs-3 pad-cathegory">
											<a href="<?= Url::to(["deviser/store-edit", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id, 'category' => $category->short_id])?>">
												<figure class="cathegory">
													<img class="<?= ($selectedCategory->short_id==$category->short_id) ? 'active' : '' ?>" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($category->getDeviserProduct()->getMainImage())->resize(240, 175) ?>">
													<figcaption>
														<span class="name">
															<?= Utils::l($category->name) ?>
														</span>
													</figcaption>
												</figure>
											</a>
										</div>
									<?php } ?>
								</div>
							<?php } ?>
							<div class="store-grid">
								<div class="title-wrapper">
									<div class="title-wrapper">
										<span class="title">Unpublished works</span>
									</div>
									<p class="message-tagline">Only you are able to see your unpublished works.</p>
									<div class="row m-0">
										<div class="col-md-3 pad-grid" ng-repeat="product in editStoreCtrl.unpublishedProducts">

												<a href="">
													<div class="grid">
														<figure class="effect-zoe">
															<img class="grid-image" ng-src="{{product.main_photo}}">
															<figcaption>
																<p class="instauser">{{product.name[editStoreCtrl.language]}}</p>
																<p class="price">€ 0</p>
															</figcaption>
														</figure>
													</div>
												</a>
				
										</div>
									</div>
									<div class="mt-20">
										<span class="title"><?= Utils::l($selectedCategory->name) ?></span>
									</div>
									<nav class="products-menu">
										<ul>
											<?php if (count($selectedCategory->getDeviserSubcategories()) > 1) { ?>
												<?php foreach ($selectedCategory->getDeviserSubcategories() as $i => $subcategory) { ?>
													<li>
														<a href="<?= Url::to(["deviser/store-edit", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id, 'category' => $selectedCategory->short_id, 'subcategory' => $subcategory->short_id])?>" class="<?= ($selectedSubcategory->short_id==$subcategory->short_id) ? 'active' : '' ?>"><?= Utils::l($subcategory["name"]) ?></a>
													</li>
												<?php } ?>
											<?php } ?>
										</ul>
									</nav>
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