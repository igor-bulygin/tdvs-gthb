<?php
use app\assets\desktop\deviser\EditStoreAsset;
use app\components\MakeProfilePublic;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\helpers\Utils;
use app\models\Category;
use app\models\Person;
use app\models\Product;
use yii\helpers\Json;

EditStoreAsset::register($this);

/** @var Person $person */
/** @var Product $product */
/** @var Category $category */
/** @var Category $selectedCategory */

$this->title = $person->personalInfoMapping->getBrandName() . ' - Todevise';

// use params to share data between views :(
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'store';
$this->params['person_links_target'] = 'edit_view';
$this->params['person_menu_store_categories'] = $categories;

$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>
<div ng-controller="editStoreCtrl as editStoreCtrl">
	<div class="success-bar" ng-if="editStoreCtrl.view_published_topbar" style="background: #b8e986; height: 50px;" ng-cloak>
		<p class="text-center">Your work has been published successfully.</p>
		<span ng-click="editStoreCtrl.view_published_topbar=false">
			<i class="ion-android-close"></i>
		</span>
	</div>
	<?php if ($person->isDraft()) { ?>
		<?= MakeProfilePublic::widget() ?>
	<?php } ?>
	<?= PersonHeader::widget() ?>

		<div class="store">
			<div class="container">
				<div class="row">
					<div class="col-md-2">
						<?= PersonMenu::widget() ?>
					</div>
					<div class="col-md-10">
						
						<div class="content-store">
							<?php if ($unpublishedWorks || count($categories) > 1) { ?>
								<div class="cathegory-wrapper">
									<?php if ($unpublishedWorks) { ?>
										<div class="col-md-3 col-sm-3 col-xs-3 pad-cathegory">
											<a href="<?= $person->getStoreEditLink(['product_state' => \app\models\Product2::PRODUCT_STATE_DRAFT])?>">
												<div class="unpublished-square" ng-click="editStoreCtrl.show_unpublished_works()">
													<p>Unpublished<br>works</p>
												</div>
											</a>
										</div>
									<?php } ?>
									<?php foreach ($categories as $i => $category) { ?>
										<div class="col-md-3 col-sm-3 col-xs-3 pad-cathegory">
											<a href="<?= $person->getStoreEditLink(['categoryId' => $category->short_id])?>">
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
															<a href="<?= $person->getStoreEditLink(['category' => $selectedCategory->short_id, 'subcategory' => $subcategory->short_id])?>" class="<?= ($selectedSubcategory->short_id==$subcategory->short_id) ? 'active' : '' ?>"><?= Utils::l($subcategory["name"]) ?></a>
														</li>
													<?php } ?>
												<?php } ?>
											</ul>
										</nav>
										<div class="mesonry-row" dnd-list="editStoreCtrl.products">
											<div class="menu-category list-group" ng-repeat="product in editStoreCtrl.products | publishedProduct" ng-if="product.main_photo" dnd-draggable="product" dnd-effect-allowed="move" dnd-moved="editStoreCtrl.update($index, product)">
												<div class="grid">
													<figure class="effect-zoe">
														<span class="close-product-icon" ng-click="editStoreCtrl.open_modal_delete(product.id)">
															<i class="ion-android-close"></i>
														</span>
														<a ng-href="{{product.link}}">
															<img class="grid-image" ng-src="{{product.main_photo}}">
														</a>
														<figcaption>
															<a ng-href="{{product.link}}">
																<p class="instauser">{{product.name}}</p>
																<p class="price">€{{product.min_price}}</p>
															</a>
															<a href="" class="edit-product-icon" ng-href="{{product.edit_link}}" title="Edit work">
																<i class="ion-edit"></i>
															</a>
														</figcaption>
													</figure>
												</div>
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