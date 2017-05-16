<?php
use app\assets\desktop\pub\Index2Asset;
use app\helpers\Utils;
use app\models\Person;

Index2Asset::register($this);

$this->title = 'Todevise / Home';

/** @var Person[][] $devisers */
/** @var int $totalDevisers */
/** @var Person[][] $influencers */
/** @var int $totalInfluencers */
/** @var \app\models\Product2[] $works12 */
/** @var \app\models\Product2[] $works3 */
/** @var \app\models\Product2[][] $moreWork */
/** @var \app\models\Box[] $boxes */
/** @var \app\models\Story[] $stories */
/** @var array $banners */

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
								<image-hover-buttons product-id="{{'<?= $work->short_id ?>'}}" is-loved="{{'<?=$work->isLovedByCurrentUser() ? 1 : 0 ?>'}}" is-mine="{{'<?= $work->isWorkFromCurrentUser() ? 1 : 0 ?>'}}">
									<a href="<?= $work->getViewLink()?>">
										<img class="grid-image"
											src="<?= Utils::url_scheme() ?><?= Utils::thumborize($work->getMainImage())->resize(362, 450) ?>">
									</a>
								</image-hover-buttons>
								<a href="<?= $work->getViewLink()?>">
									<figcaption>
										<p class="instauser">
											<?= Utils::l($work->name) ?>
										</p>
										<p class="price">€ <?= $work->getMinimumPrice() ?></p>
									</figcaption>
								</a>
							</figure>
						</div>
					
				</div>
			<?php } ?>
			<?php foreach ($works3 as $i => $work) { ?>
				<div class="col-md-4 col-sm-4 pad-grid pad-grid-h">
						<div class="grid">
							<figure class="effect-zoe">
								<image-hover-buttons product-id="{{'<?= $work->short_id ?>'}}" is-loved="{{'<?=$work->isLovedByCurrentUser() ? 1 : 0 ?>'}}" is-mine="{{'<?= $work->isWorkFromCurrentUser() ? 1 : 0 ?>'}}">
									<a href="<?= $work->getViewLink()?>">
									<img class="grid-image"
										src="<?= Utils::url_scheme() ?><?= Utils::thumborize($work->getMainImage())->resize(375, 220) ?>">
									</a>
								</image-hover-buttons>
								<a href="<?= $work->getViewLink()?>">
									<figcaption>
										<p class="instauser">
											<?= Utils::l($work->name) ?>
										</p>
										<p class="price">€ <?= $work->getMinimumPrice() ?></p>
									</figcaption>
								</a>
							</figure>
						</div>
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
						<?php foreach ($group as $k => $deviser) { ?>
						<div class="col-md-15 col-sm-15 col-xs-6 pad-showcase">
								<a href="<?= $deviser->getStoreLink()?>">
								<figure class="showcase">
								<button class="btn btn-default btn-follow"><i class="ion-star"></i><span>Follow</span>
								</button>
								<img src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getHeaderBackgroundImage())->resize(350, 344) ?>" class="showcase-image">
								<figcaption>
									<img class="showcase-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getAvatarImage())->resize(0, 110) ?>">
									<span class="name"><?= $deviser->getName() ?></span>
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

<?php if ($boxes) { ?>
	<section class="grid-wrapper">
		<div class="container">

		<?php if ($stories) { ?>
			<div class="col-lg-8">
		<?php } ?>

				<div class="section-title">
					Boxes
				</div>

				<div class="row no-mar">
					<?php foreach ($boxes as $box) {
						$products = $box->getProductsPreview(); ?>
						<div class="<?=$stories ? 'col-md-4' : 'col-md-4'?> col-xs-6 pad-grid">
							<div class="boxes-wrapper home">
								<?php if (empty($products)) { ?>
									<div class="empty-box">
										<span class="empty-title">Empty box</span>
									</div>
								<?php } else {
									$count  = 1;
									foreach ($products as $product) {
										if ($count > 3) {
											break;
										} ?>
										<a href="<?= $box->getViewLink()?>">
											<img class="grid-image <?=count($products) >= 3 && $count < 3 ? 'small-photo-box' : ''?>" src="<?=$product['box_photo']?>">
										</a>
										<?php
										$count ++;
									} ?>
								<?php } ?>
								<a class="group-box-title" href="<?= $box->getViewLink() ?>">
									<span><?=$box->name?> (<?=count($products)?>)</span>
								</a>
							</div>
						</div>
					<?php } ?>
				</div>

		<?php if ($stories) { ?>
			</div>

			<div class="col-lg-4">
				<div class="section-title">
					Stories
				</div>
				<?php foreach ($stories as $story) { ?>
					<a href="<?=$story->getViewLink()?>">
						<div class="storie-box-wrapper">
							<div class="storie-box-text">
								<h5><?=$story->title?></h5>
								<p><?=$story->getFirstText()?></p>
								<div>
									<div class="loved-comments-wrapper">
										<div class="loved-wrapper">
											<i class="ion-ios-heart"></i>
											<span>342</span>
										</div>
										<div class="comments-wrapper">
											<i class="ion-chatbox"></i>
											<span>15</span>
										</div>
									</div>
								</div>
							</div>
							<?php if ($story->mainMediaMapping->type == \app\models\StoryMainMedia::STORY_MAIN_MEDIA_TYPE_PHOTO) { ?>
								<div class="storie-box-image">
									<img src="<?=$story->getMainPhotoUrl()?>">
								</div>
							<?php } ?>
					</div>
					</a>
				<?php } ?>
			</div>

		<?php } ?>

		</div>
	</section>
<?php } ?>

<?php if ($totalInfluencers) { ?>
	<section class="showcase-wrapper">
		<div class="container">
			<h3>Discover the works they love</h3>
			<div class="section-title">
				Influencers
			</div>
			<!-- Controls -->
			<div class="prev-next-wrapper">
				<?php if ($totalInfluencers > 3) { ?>
					<a class="prev" href="#carousel-influencers" role="button" data-slide="prev">
						<i class="ion-ios-arrow-left"></i>
						<span>Previous</span>
					</a>
					<a class="next" href="#carousel-influencers" role="button" data-slide="next">
						<span>Next</span>
						<i class="ion-ios-arrow-right"></i>
					</a>
				<?php } ?>
			</div>
			<div class="<?= $totalInfluencers > 3 ? 'carousel slide' : ''?>" id="carousel-influencers" data-ride="carousel">
				<div class="<?= $totalInfluencers > 3 ? 'carousel-inner' : ''?>" role="listbox">
					<?php foreach ($influencers as $i => $group) { ?>
						<div class="item <?= ($i==0) ? 'active' : '' ?>">
							<?php foreach ($group as $k => $influencer) { ?>
								<div class="col-md-4 col-sm-4 col-xs-6 pad-showcase">
									<a href="<?= $influencer->getLovedLink()?>">
										<figure class="showcase showcase-influencers">
											<button class="btn btn-default btn-follow"><i class="ion-star"></i><span>Follow</span>
											</button>
											<img src="<?= Utils::url_scheme() ?><?= Utils::thumborize($influencer->getHeaderBackgroundImage())->resize(350, 344) ?>" class="showcase-image">
											<figcaption>
												<img class="showcase-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($influencer->getAvatarImage())->resize(0, 110) ?>">
												<span class="name"><?= $influencer->getName() ?></span>
												<span class="location"><?= $influencer->personalInfoMapping->getCityLabel() ?></span>
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
<?php } ?>

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
					<div class="grid">
						<figure class="effect-zoe">
							<image-hover-buttons product-id="{{'<?= $work->short_id ?>'}}" is-loved="{{'<?=$work->isLovedByCurrentUser() ? 1 : 0 ?>'}}" is-mine="{{'<?= $work->isWorkFromCurrentUser() ? 1 : 0 ?>'}}">
								<a href="<?= $work->getViewLink()?>">
									<img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($work->getMainImage())->resize(362, 450) ?>">
								</a>
							</image-hover-buttons>
							<a href="<?= $work->getViewLink()?>">
							<figcaption>
								<p class="instauser">
									<?= Utils::l($work->name) ?>
								</p>
								<p class="price">€ <?= $work->getMinimumPrice() ?></p>
							</figcaption>
							</a>
						</figure>
					</div>
			</div>
			<?php } ?>

			<?php foreach ($worksGroup["three"] as $i => $work) { ?>
			<div class="col-md-4 col-sm-4 pad-grid pad-grid-h">
					<div class="grid">
						<figure class="effect-zoe">
							<image-hover-buttons product-id="{{'<?= $work->short_id ?>'}}" is-loved="{{'<?=$work->isLovedByCurrentUser() ? 1 : 0 ?>'}}" is-mine="{{'<?= $work->isWorkFromCurrentUser() ? 1 : 0 ?>'}}">
								<a href="<?= $work->getViewLink()?>">
									<img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($work->getMainImage())->resize(375, 220) ?>">
								</a>
							</image-hover-buttons>
							<a href="<?= $work->getViewLink()?>">
								<figcaption>
									<p class="instauser">
										<?= Utils::l($work->name) ?>
									</p>
									<p class="price">€ <?= $work->getMinimumPrice() ?></p>
								</figcaption>
							</a>
						</figure>
					</div>
			</div>
			<?php } ?>
			<?php } ?>
		</div>
	</div>
</section>
<!-- /GRID -->