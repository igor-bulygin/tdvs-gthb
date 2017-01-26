<?php
use app\assets\desktop\pub\DeviserStoreViewAsset;
use app\components\DeviserHeader;
use app\components\DeviserMenu;
use app\helpers\Utils;
use app\models\Category;
use app\models\Person;
use app\models\Product;
use yii\helpers\Url;

DeviserStoreViewAsset::register($this);

/** @var Person $deviser */
/** @var Product $product */
/** @var Category $category */
/** @var Category $selectedCategory */

$this->title = $deviser->personalInfoMapping->getBrandName() . ' - Todevise';

// use params to share data between views :(
$this->params['deviser_menu_categories'] = $categories;
$this->params['deviser_menu_active_option'] = 'store';
$this->params['deviser_links_target'] = 'public_view';
$this->params['deviser'] = $deviser;

?>

<?= DeviserHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= DeviserMenu::widget() ?>
			</div>
			<div class="col-md-10">
				<div class="content-store">
					<?php if ($unpublishedWorks || count($categories) > 1) { ?>
                        <div class="cathegory-wrapper">
                            <div class="col-md-3 col-sm-3 col-xs-3 pad-cathegory">
                                <a href="<?= Url::to(["deviser/store-edit", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id, 'product_state' => \app\models\Product2::PRODUCT_STATE_DRAFT])?>">
                                    <p>Unpublished<br>works</p>
                                </a>
                            </div>
                            <?php foreach ($categories as $i => $category) { ?>
                            <div class="col-md-3 col-sm-3 col-xs-3 pad-cathegory">
                                <a href="<?= Url::to(["deviser/store", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id, 'category' => $category->short_id])?>">
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
										<a href="<?= Url::to(["deviser/store", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id, 'category' => $selectedCategory->short_id, 'subcategory' => $subcategory->short_id])?>" class="<?= ($selectedSubcategory->short_id==$subcategory->short_id) ? 'active' : '' ?>"><?= Utils::l($subcategory["name"]) ?></a>
									</li>
								<?php } ?>
								<?php } ?>
							</ul>
						</nav>
						<div id="macy-container">
							<?php foreach ($products as $i => $product) { ?>
								<div class="menu-category list-group">
									<a href="<?= Url::to(["product/detail", "slug" => $product->slug, 'product_id' => $product->short_id])?>">
										<div class="grid">
											<figure class="effect-zoe">
												<img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize(400, 0) ?>">
												<figcaption>
													<p class="instauser">
														<?= $product->name ?>
													</p>
													<p class="price">€ <?= $product->getMinimumPrice() ?></p>
												</figcaption>
											</figure>
										</div>
									</a>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
