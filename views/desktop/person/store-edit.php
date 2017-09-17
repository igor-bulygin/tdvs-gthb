<?php

use app\assets\desktop\deviser\GlobalAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\helpers\Utils;
use app\models\Category;
use app\models\Person;
use app\models\Product;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */
/** @var Product $product */
/** @var Category $category */
/** @var Category $selectedCategory */

$this->title = Yii::t('app/public',
	'EDIT_WORKS_BY_PERSON_NAME',
	['person_name' => $person->getName()]
);

// use params to share data between views :(
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'store';
$this->params['person_links_target'] = 'edit_view';
$this->params['person_menu_store_categories'] = $categories;

$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>
<div ng-controller="editStoreCtrl as editStoreCtrl">
	<div class="success-bar" ng-if="editStoreCtrl.view_published_topbar" style="background: #b8e986; height: 50px;" ng-cloak>
		<p class="text-center"><span translate="person.store.WORK_PUBLISHED"></span></p>
		<span ng-click="editStoreCtrl.view_published_topbar=false">
			<i class="ion-android-close"></i>
		</span>
	</div>

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
											<a href="<?= $person->getStoreEditLink(['product_state' => \app\models\Product::PRODUCT_STATE_DRAFT])?>">
												<div class="unpublished-square" ng-click="editStoreCtrl.show_unpublished_works()">
													<p><span translate="person.store.UNPUBLISHED_WORKS_BR"></span></p>
												</div>
											</a>
										</div>
									<?php } ?>
									<?php foreach ($categories as $i => $category) { ?>
										<div class="col-md-3 col-sm-3 col-xs-3 pad-cathegory">
											<a href="<?= $person->getStoreEditLink(['category' => $category->short_id])?>">
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
									<div ng-if="editStoreCtrl.view_unpublished_works" ng-cloak>
										<div class="title-wrapper">
											<span class="title"><span translate="person.store.UNPUBLISHED_WORKS"></span></span>
										</div>
										<p class="message-tagline"><span translate="person.store.UNPUBLISHED_WORKS_SEE"></span></p>
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
										<div ng-if="editStoreCtrl.products.length === 0" ng-cloak>
											<div class="text-center">
											<p><span translate="person.store.ADD_FIRST_WORK"></span></p>
											<a class="btn btn-default btn-red btn-add-work" href="<?=$person->getCreateWorkLink()?>"><span translate="person.ADD_WORK"></span></a>
											</div>
										</div>
										<div class="mesonry-row" ui-sortable="editStoreCtrl.sortableOptions" ng-model="editStoreCtrl.products" ng-if="editStoreCtrl.products.length > 0" ng-cloak>
											<div class="menu-category list-group" ng-repeat="product in editStoreCtrl.products | publishedProduct" ng-if="product.main_photo">
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
								<p><span translate="person.DELETE_WORK_QUESTION"></span></p>
							</div>
							<div class="modal-footer">
								<button class="btn btn-default btn-red pull-left" ng-click="modalDeleteProductCtrl.close()"><span translate="global.CANCEL"></span></button>
								<button class="btn btn-default pull-right" ng-click="modalDeleteProductCtrl.ok()"><span translate="global.DELETE"></span></button>
							</div>
						</div>
						</script>
					</div>
				</div>
			</div>
		</div>
</div>