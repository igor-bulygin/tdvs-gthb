<?php
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

$this->title = 'Todevise / Home';

// use params to share data between views :(
$this->params['footer_mode'] = 'collapsed';

/** @var Person $deviser */
/** @var Product $work */

?>

<!-- BANNER-->
<div class="bs-example" data-example-id="simple-carousel">
	<div class="carousel slide" id="carousel-example-generic" data-ride="carousel">
		<ol class="carousel-indicators">
			<?php foreach ($banners as $i => $banner) { ?>
				<li data-target="#carousel-example-generic" data-slide-to="<?= $i ?>" class="<?= ($banner["active"]) ? 'active' : '' ?>"></li>
			<?php } ?>
		</ol>
		<div class="carousel-inner" role="listbox">
			<?php foreach ($banners as $i => $banner) { ?>
				<div class="item <?= ($banner["active"]) ? 'active' : '' ?>">
					<img src="<?= $banner["img"] ?>" alt="<?= $banner["alt"] ?>" title="">
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<!-- /BANNER -->

<!-- SUB-BANNER -->
<section class="sub-banner">
	<div class="container container-sub-baner">
		<div class="row">
			<div class="col-sm-4 col-xs-6 title-wrapper righty">
				<h2 class="title-1"><span class="serif">The</span>store</h2>
				<p class="tagline-1">Find products that will make you part of the future</p>
			</div>
			<div class="col-sm-4 col-xs-6 title-wrapper">
				<h2>Social<br/><span class="serif">experience</span></h2>
				<p class="tagline-2">Show the world what you like &amp; build a community</p>
			</div>
			<div class="col-sm-4 title-wrapper">
				<h2>Affiliate<br/><span class="serif">for all</span></h2>
				<p class="tagline-3">Love a product. People buy it. You earn money.</p>
			</div>
		</div>
	</div>
</section>
<!-- /SUB-BANNER -->

<!-- GRID -->
<section class="grid-wrapper">
	<div class="container">
		<div class="section-title">
			Highlighted Works
		</div>
		<div>
			<?php foreach ($works12 as $i => $work) { ?>
				<div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
					<a href="<?= Url::to(["public/product-b", "slug" => Utils::l($work->slug), 'product_id' => $work->short_id])?>">
						<div class="grid">
							<figure class="effect-zoe">
								<img class="grid-image"
								     src="<?= Utils::url_scheme() ?><?= Utils::thumborize($work->getMainImage())->resize(362, 450) ?>">
								<figcaption>
									<p class="instauser">
										<?= Utils::l($work->name) ?>
									</p>
									<p class="price">€ <?= $work->getMinimumPrice() ?></p>
								</figcaption>
							</figure>
						</div>
					</a>
				</div>
			<?php } ?>
			<?php foreach ($works3 as $i => $work) { ?>
				<div class="col-md-4 col-sm-4 pad-grid pad-grid-h">
					<a href="<?= Url::to(["public/product-b", "slug" => Utils::l($work->slug), 'product_id' => $work->short_id])?>">
						<div class="grid">
							<figure class="effect-zoe">
								<img class="grid-image"
								     src="<?= Utils::url_scheme() ?><?= Utils::thumborize($work->getMainImage())->resize(375, 220) ?>">
								<figcaption>
									<p class="instauser">
										<?= Utils::l($work->name) ?>
									</p>
									<p class="price">€ <?= $work->getMinimumPrice() ?></p>
								</figcaption>
							</figure>
						</div>
					</a>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
<!-- /GRID -->

<!-- SHOWCASE -->
<section class="showcase-wrapper">
	<div class="container">
		<h3>Artists, designers, creators who<br>
			shape outstanding works</h3>
		<div class="section-title">
			Devisers
		</div>
		<div>
			<?php foreach ($devisers as $i => $deviser) { ?>
				<div class="col-md-3 col-sm-3 col-xs-6 pad-showcase">
					<a href="<?= Url::to(["deviser/store", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">
						<figure class="showcase">
							<button class="btn btn-default btn-follow"><i class="ion-star"></i><span>Follow</span>
							</button>
							<img class="showcase-image"
							     src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getHeaderBackgroundImage())->resize(350, 344) ?>">
							<figcaption>
								<img class="showcase-image"
								     src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getAvatarImage())->resize(0, 110) ?>">
								<span class="name"><?= $deviser->getBrandName() ?></span>
								<span class="location"><?= $deviser->getCityLabel() ?></span>
							</figcaption>
						</figure>
					</a>
				</div>
			<?php } ?>
		</div>
	</div>
</section>
<!-- /SHOWCASE -->

<!-- GRID -->
<section class="grid-wrapper">
	<div class="container">
		<div class="section-title">
			Highlighted Works
		</div>
		<div>
			<?php foreach ($moreWork as $worksGroup) { ?>
			<?php foreach ($worksGroup["twelve"] as $i => $work) { ?>
			<div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
				<a href="<?= Url::to(["public/product-b", "slug" => Utils::l($work->slug), 'product_id' => $work->short_id])?>">
					<div class="grid">
						<figure class="effect-zoe">
							<img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($work->getMainImage())->resize(362, 450) ?>">
							<figcaption>
								<p class="instauser">
									<?= Utils::l($work->name) ?>
								</p>
								<p class="price">€ <?= $work->getMinimumPrice() ?></p>
							</figcaption>
						</figure>
					</div>
				</a>
			</div>
			<?php } ?>

			<?php foreach ($worksGroup["three"] as $i => $work) { ?>
			<div class="col-md-4 col-sm-4 pad-grid pad-grid-h">
				<a href="<?= Url::to(["public/product-b", "slug" => Utils::l($work->slug), 'product_id' => $work->short_id])?>">
					<div class="grid">
						<figure class="effect-zoe">
							<img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($work->getMainImage())->resize(375, 220) ?>">
							<figcaption>
								<p class="instauser">
									<?= Utils::l($work->name) ?>
								</p>
								<p class="price">€ <?= $work->getMinimumPrice() ?></p>
							</figcaption>
						</figure>
					</div>
				</a>
			</div>
			<?php } ?>
			<?php } ?>
		</div>
	</div>
</section>
<!-- /GRID -->