<?php
use app\assets\desktop\deviser\EditStoreAsset;
use app\assets\desktop\pub\Index2Asset;
use app\components\DeviserHeader;
use app\components\DeviserMakeProfilePublic;
use app\components\DeviserMenu;
use app\helpers\Utils;
use app\models\Category;
use app\models\Person;
use app\models\Product;
use yii\helpers\Url;

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
<div ng-controller="editStoreCtrl as editStoreCtrl">
	<div class="success-bar" ng-if="editStoreCtrl.view_published_topbar" style="background: #b8e986; height: 50px;">
		<p class="text-center">Your work has been published successfully.</p>
		<span ng-click="editStoreCtrl.view_published_topbar=false">
			<i class="ion-android-close"></i>
		</span>
	</div>
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
					<div class="col-md-10">
						
						<div class="content-store">
							<?php if (count($categories) > 1) { ?>
								<div class="cathegory-wrapper">
									<div class="col-md-3 col-sm-3 col-xs-3 pad-cathegory">
										<a href="<?= Url::to(["deviser/store-edit", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id, 'product_state' => \app\models\Product2::PRODUCT_STATE_DRAFT])?>">
											<div class="unpublished-square" ng-click="editStoreCtrl.show_unpublished_works()">
												<p>Unpublished<br>works</p>
											</div>
										</a>
									</div>
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
									<div ng-if="editStoreCtrl.view_unpublished_works">
										<div class="title-wrapper">
											<span class="title">Unpublished works</span>
										</div>
										<p class="message-tagline">Only you are able to see your unpublished works.</p>
										<div class="row m-0">
											<div class="col-md-3 pad-grid" ng-repeat="product in editStoreCtrl.products | draftProduct">
												<div class="grid">
													<figure class="effect-zoe">
														<span class="close-product-icon" ng-click="editStoreCtrl.open_modal_delete(product.id)">
															<i class="ion-android-close"></i>
														</span>
														<img class="grid-image" ng-src="{{product.main_photo || '/imgs/product_placeholder.png'}}">
														<figcaption>
															<p class="instauser">{{product.name || "Untitled"}}</p>
															<p class="price">€{{product.min_price || '-'}}</p>
															<a class="edit-product-icon" ng-href="{{product.edit_link}}" title="Edit work">
																<i class="ion-edit"></i>
															</a>
														</figcaption>
													</figure>
												</div>
											</div>
										</div>
									</div>
									<div ng-if="!editStoreCtrl.view_unpublished_works">
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
											<div class="menu-category list-group" ng-repeat="product in editStoreCtrl.products | publishedProduct" ng-if="product.main_photo" dnd-draggable="product" dnd-effect-allowed="move" dnd-moved="editStoreCtrl.update($index, product)">
												<a href="">
													<div class="grid">
														<figure class="effect-zoe">
															<span class="close-product-icon" ng-click="editStoreCtrl.open_modal_delete(product.id)">
																<i class="ion-android-close"></i>
															</span>
															<img class="grid-image" ng-src="{{product.main_photo}}">
															<figcaption>
																<p class="instauser">{{product.name}}</p>
																<p class="price">€{{product.min_price}}</p>
																<a href="" class="edit-product-icon" ng-href="{{product.edit_link}}" title="Edit work">
																	<i class="ion-edit"></i>
																</a>
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
						<script type="text/ng-template" id="modalDeleteProduct.html">
						<div class="modal-delete">
							<div class="modal-header">
								<h3 class="modal-title"></h3>
							</div>
							<div class="modal-body">
								<p>Are you sure you want to delete this work?</p>
							</div>
							<div class="modal-footer">
								<button class="btn btn-default btn-green pull-left" ng-click="modalDeleteProductCtrl.close()">Cancel</button>
								<button class="btn btn-default pull-right" ng-click="modalDeleteProductCtrl.ok()">DELETE</button>
							</div>
						</div>
						</script>

					</div>
				</div>
			</div>
		</div>
</div>