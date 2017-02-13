<?php
use app\assets\desktop\pub\Index2Asset;
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Url;

Index2Asset::register($this);

$this->title = 'Todevise / Home';

/** @var Person $deviser */
/** @var \app\models\Product2 $work */

?>

<!-- BANNER-->
<div class="bs-example" data-example-id="simple-carousel">
	<div class="carousel slide" id="carousel-example-generic" data-ride="carousel">
		<ol class="carousel-indicators">
			<?php foreach ($banners as $i => $banner) { ?>
				<li data-target="#carousel-example-generic" data-slide-to="<?= $i ?>" class="<?= ($banner["active"]) ? 'active' : '' ?>"></li>
			<?php } ?>
		</ol>
		<div class="carousel-inner home-carousel" role="listbox">
			<?php foreach ($banners as $i => $banner) { ?>

					<div class="item <?= ($banner["active"]) ? 'active' : '' ?>">
					 	<a href="<?= isset($banner['url']) ? $banner['url'] : '#'?>">
							<img src="<?= $banner["img"] ?>" alt="<?= $banner["alt"] ?>" title="">
						</a>
					</div>
				
			<?php } ?>
		</div>
		<!-- Controls -->
		  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left banner-btn" aria-hidden="true">
				<i class="ion-ios-arrow-left"></i>
			</span>
			<span class="sr-only">Previous</span>
		  </a>
		  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right banner-btn" aria-hidden="true">
				<i class="ion-ios-arrow-right"></i>
			</span>
			<span class="sr-only">Next</span>
		  </a>
	</div>
</div>
<!-- /BANNER -->

<!-- SUB-BANNER -->
<section class="sub-banner">
	<div class="container container-sub-baner">
		<div class="row">
			<div class="col-sm-4 col-xs-6 title-wrapper title-1 righty">
				<h2 class="title-1"><span class="serif">The</span>store</h2>
				<p class="tagline-1">Find products that will make you part of the future</p>
			</div>
			<div class="col-sm-4 col-xs-6 title-wrapper">
				<h2 class="title-2">Social<br/><span class="serif">experience</span></h2>
				<p class="tagline-2">Show the world what you like &amp; build a community</p>
			</div>
			<div class="col-sm-4 title-wrapper title-3">
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
						<div class="grid">
							<figure class="effect-zoe">
								<image-hover-buttons product-id="{{'<?= $work->short_id ?>'}}" is-loved="{{'<?=$work->isLovedByCurrentUser() ? 1 : 0 ?>'}}">
									<a href="<?= Url::to(["product/detail", "slug" => Utils::l($work->slug), 'product_id' => $work->short_id])?>">
									<img class="grid-image"
										src="<?= Utils::url_scheme() ?><?= Utils::thumborize($work->getMainImage())->resize(362, 450) ?>">
										</a>
								</image-hover-buttons>
								<figcaption>
									<p class="instauser">
										<?= Utils::l($work->name) ?>
									</p>
									<p class="price">€ <?= $work->getMinimumPrice() ?></p>
								</figcaption>
							</figure>
						</div>
					
				</div>
			<?php } ?>
			<?php foreach ($works3 as $i => $work) { ?>
				<div class="col-md-4 col-sm-4 pad-grid pad-grid-h">
					<a href="<?= Url::to(["product/detail", "slug" => Utils::l($work->slug), 'product_id' => $work->short_id])?>">
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
			<!-- Controls -->
			<div class="prev-next-wrapper">
				<?php if ($totalDevisers > 5) { ?>
					<a class="prev" href="#carousel-devisers" role="button" data-slide="prev">
						<i class="ion-ios-arrow-left"></i>
						<span>Previous</span>
					</a>
					<a class="next" href="#carousel-devisers" role="button" data-slide="next">
						<span>Next</span>
						<i class="ion-ios-arrow-right"></i>
					</a>
				<?php } ?>
			</div>
				<div class="<?= $totalDevisers > 5 ? 'carousel slide' : ''?>" id="carousel-devisers" data-ride="carousel">
					<div class="<?= $totalDevisers > 5 ? 'carousel-inner' : ''?>" role="listbox">
						<?php foreach ($devisers as $i => $group) { ?>
					<div class="item <?= ($i==0) ? 'active' : '' ?>">
						<?php foreach ($group as $i => $deviser) { ?>
						<div class="col-md-15 col-sm-15 col-xs-6 pad-showcase">
								<a href="<?= Url::to(["deviser/store", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">
								<figure class="showcase">
								<button class="btn btn-default btn-follow"><i class="ion-star"></i><span>Follow</span>
								</button>
								<img src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getHeaderBackgroundImage())->resize(350, 344) ?>" class="showcase-image">
								<figcaption>
									<img class="showcase-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getAvatarImage())->resize(0, 110) ?>">
									<span class="name"><?= $deviser->personalInfoMapping->getBrandName() ?></span>
									<span class="location"><?= $deviser->personalInfoMapping->getCityLabel() ?></span>
								</figcaption>
								</figure>
								</a>
							</div>
						<?php } ?>
					</div>
					<?php } ?>
					</div>
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
				<a href="<?= Url::to(["product/detail", "slug" => Utils::l($work->slug), 'product_id' => $work->short_id])?>">
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
				<a href="<?= Url::to(["product/detail", "slug" => Utils::l($work->slug), 'product_id' => $work->short_id])?>">
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