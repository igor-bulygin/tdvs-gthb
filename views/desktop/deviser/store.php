<?php
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
use app\assets\desktop\pub\IndexAsset;
use app\assets\desktop\pub\Index2Asset;

Index2Asset::register($this);

/** @var Person $deviser */
/** @var Product $product */
/** @var Category $category */
/** @var Category $selectedCategory */

$this->title = $deviser->getBrandName() . ' - Todevise';

?>

<div class="banner-deviser">
	<div class="container pad-about">
		<img class="cover" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getHeaderBackgroundImage())->resize(1280, 450) ?>">
		<div class="banner-deviser-content">
			<div class="grey-overlay"></div>
			<div class="container">
				<div class="deviser-profile">
					<div class="avatar">
						<img class="cover" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getAvatarImage())->resize(340, 340) ?>">
					</div>
					<div class="deviser-data">
						<div class="name">
							<?= $deviser->getBrandName() ?>
						</div>
						<div class="location">
							<?= $deviser->getCityLabel() ?>
						</div>
						<div class="description">
							<?= $deviser->getShortDescription() ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<nav class="menu-store" data-spy="affix" data-offset-top="450">
					<ul class="mt-0">
						<li>
							<a class="active" href="#">Store</a>
							<ul class="submenu-store">
								<?php foreach ($categories as $i => $category) { ?>
								<li class="<?= (($i==0) ? 'mt10' : (($i==(count($categories)-1)) ? 'mt20' : '')) ?>">
									<a href="<?= Url::to(["deviser/store", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id, 'category' => $category->short_id])?>"><?= Utils::l($category->name) ?></a>
								</li>
								<?php } ?>
							</ul>
						</li>
					</ul>
					<ul>
						<li>
							<a href="<?= Url::to(["deviser/about", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">About</a>
						</li>
						<li>
							<a href="<?= Url::to(["deviser/press", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">Press</a>
						</li>
						<li>
							<a href="#">Videos</a>
						</li>
						<li>
							<a href="#">FAQ</a>
						</li>
					</ul>
				</nav>
			</div>
			<div class="col-md-10">
				<div class="content-store">
					<div class="cathegory-wrapper">
						<?php if (count($categories) > 1) { ?>
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
						<?php } ?>

					</div>
					<div class="store-grid">
						<div class="title-wrapper">
							<span class="title"><?= Utils::l($selectedCategory->name) ?></span>
						</div>
						<nav class="products-menu">
							<ul>
								<?php if (count($selectedCategory->getDeviserSubcategories()) > 1) { ?>
								<?php foreach ($selectedCategory->getDeviserSubcategories() as $i => $category) { ?>
									<li>
										<a href="#" class="<?= ($i==0) ? 'active' : '' ?>"><?= Utils::l($category["name"]) ?></a>
									</li>
								<?php } ?>
								<?php } ?>
							</ul>
						</nav>
						<div class="mesonry-row">
							<?php foreach ($products as $i => $product) { ?>
								<div class="menu-category list-group">
									<a href="<?= Url::to(["product/detail", "slug" => Utils::l($product->slug), 'product_id' => $product->short_id])?>">
										<div class="grid">
											<figure class="effect-zoe">
												<img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize(400, 0) ?>">
												<figcaption>
													<p class="instauser">
														<?= Utils::l($product->name) ?>
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
