<?php
use app\assets\desktop\pub\StoreViewAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\helpers\Utils;
use app\models\Category;
use app\models\Person;
use app\models\Product2;

StoreViewAsset::register($this);

/** @var Person $person */
/** @var Product2[] $products */
/** @var Category $category */
/** @var Category $selectedCategory */

$this->title = $person->personalInfoMapping->getBrandName() . ' - Todevise';

// use params to share data between views :(
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'store';
$this->params['person_links_target'] = 'public_view';
$this->params['person_menu_store_categories'] = $categories;

?>

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
                                        <div class="unpublished-square">
                                            <p>Unpublished<br>works</p>
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
						<div id="macy-container">
							<?php foreach ($products as $i => $product) { ?>
								<div class="menu-category list-group">
                                    <div class="grid">
                                        <figure class="effect-zoe">

											<?php /*
                                                <span class="close-product-icon" ng-click="editStoreCtrl.open_modal_delete(product.id)">
                                                    <i class="ion-android-close"></i>
                                                </span>
                                                */ ?>
                                            <image-hover-buttons product-id="{{'<?= $product->short_id ?>'}}" is-loved="{{'<?=$product->isLovedByCurrentUser() ? 1 : 0 ?>'}}" is-mine="{{'<?= $product->isWorkFromCurrentUser() ? 1 : 0 ?>'}}">
                                                <a href="<?= $product->getViewLink() ?>">
                                                    <img class="grid-image"
                                                         src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize(400, 0) ?>">
                                                </a>
                                            </image-hover-buttons>
                                            <a href="<?= $product->getViewLink()?>">
                                                <figcaption>
                                                    <p class="instauser">
														<?= $product->name ?>
                                                    </p>
                                                    <p class="price">€ <?= $product->getMinimumPrice() ?></p>
													<?php if ($person->isPersonEditable()) { ?>
                                                        <a class="edit-product-icon" href="<?= $product->getEditLink()?>" title="Edit work">
                                                            <i class="ion-edit"></i>
                                                        </a>
													<?php } ?>
                                                </figcaption>
                                            </a>
                                        </figure>
                                    </div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
