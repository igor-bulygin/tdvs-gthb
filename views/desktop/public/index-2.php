<?php

use app\assets\desktop\pub\PublicCommonAsset;
use app\helpers\Utils;
use app\models\Person;

PublicCommonAsset::register($this);

$this->title = Yii::t('app/public', 'INDEX_TITLE');

/** @var Person[][] $devisers */
/** @var int $totalDevisers */
/** @var Person[][] $influencers */
/** @var int $totalInfluencers */
/** @var \app\models\Product[] $works */
/** @var \app\models\Product[] $works12 */
/** @var \app\models\Product[] $works3 */
/** @var \app\models\Product[][] $moreWork */
/** @var \app\models\Box[] $boxes */
/** @var \app\models\Story[] $stories */
/** @var array $banners */

?>

<!-- BANNER-->
<div class="bs-example" data-example-id="simple-carousel">
	<div class="carousel slide" id="carousel-example-generic" data-ride="carousel">
		<?php if(count($banners) > 1) { ?>
		<ol class="carousel-indicators">
			<?php foreach ($banners as $i => $banner) { ?>
				<li data-target="#carousel-example-generic" data-slide-to="<?= $i ?>" class="<?= ($banner["active"]) ? 'active' : '' ?>"></li>
			<?php } ?>
		</ol>
		<?php } ?>
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
		<?php if(count($banners) > 1) { ?>
		<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left banner-btn" aria-hidden="true">
				<i class="ion-ios-arrow-left"></i>
			</span>
			<span class="sr-only"><span translate="global.PREVIOUS"></span></span>
		</a>
		<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right banner-btn" aria-hidden="true">
				<i class="ion-ios-arrow-right"></i>
			</span>
			<span class="sr-only"><span translate="global.NEXT"></span></span>
		</a>
		<?php } ?>
	</div>
</div>
<!-- /BANNER -->

<!-- SUB-BANNER -->
<section class="sub-banner">
	<div class="container container-sub-baner">
		<div class="row">
			<div class="col-sm-4 col-xs-6 title-wrapper title-1 righty">
				<div class="sub-banner-wrapper">
					<div class="sub-banner-text-left">
						<h2 class="title-1"><?=Yii::t('app/public', 'HOME_BLOCK_1_TEXT_1')?></h2>
						<p class="tagline-1"><?=Yii::t('app/public', 'HOME_BLOCK_1_TEXT_2')?></p>
					</div>
					<div class="left-point"></div>
				</div>
			</div>
			<div class="col-sm-4 col-xs-6 title-wrapper">
				<div class="sub-banner-wrapper">
					<div class="sub-banner-text-center">
						<h2 class="title-2"><?=Yii::t('app/public', 'HOME_BLOCK_2_TEXT_1')?></h2>
						<p class="tagline-2"><?=Yii::t('app/public', 'HOME_BLOCK_2_TEXT_2')?></p>
					</div>
					<div class="center-point"></div>
				</div>
			</div>
			<div class="col-sm-4 title-wrapper title-3">
				<div class="sub-banner-wrapper">
					<div class="sub-banner-text-right">
						<h2><?=Yii::t('app/public', 'HOME_BLOCK_3_TEXT_1')?></h2>
						<p class="tagline-3"><?=Yii::t('app/public', 'HOME_BLOCK_3_TEXT_2')?></p>
					</div>
					<div class="right-point"></div>
				</div>
			</div>
		</div>
	</div>
	</section>
	<!-- /SUB-BANNER -->
	<!-- SEASON BANNERS -->
	<section class="season-banners">
		<div class="container">
			<div class="row">
				<div class="col-sm-4">
					<a href="#">
						<img src="/imgs/FALL.jpg" class="responsive-image">
					</a>
				</div>
				<div class="col-sm-4">
					<a href="#">
						<img src="/imgs/JEANS.jpg" class="responsive-image">
					</a>
				</div>
				<div class="col-sm-4">
					<a href="#">
						<img src="/imgs/BASIC.jpg" class="responsive-image">
					</a>
				</div>
			</div>
		</div>
	</section>
	<!-- /SEASON BANNERS -->
	<!-- SHOWCASE -->
	<section class="showcase-wrapper">
		<div class="container">
			<h3 class="title-product-name"><?=Yii::t('app/public', 'HOME_DEVISERS')?></h3>
			<span class="subtitle-home"><?=Yii::t('app/public', 'HOME_DEVISERS_TEXT')?></span>
			<!-- Controls -->
			<div class="carusel-container">
				<?php if ($totalDevisers > 3) { ?>
					<a class="prev" href="#carousel-devisers" role="button" data-slide="prev">
						<i class="ion-ios-arrow-left"></i>
					</a>
				<?php } ?>
				<div class="carousel-devisers-container <?= $totalDevisers > 3 ? 'carousel slide' : ''?>" id="carousel-devisers" data-ride="carousel" data-interval="false">
					<div class="<?= $totalDevisers > 3 ? 'carousel-inner' : ''?>" role="listbox">
						<?php foreach ($devisers as $i => $group) { ?>
					<div class="item <?= ($i==0) ? 'active' : '' ?>">
						<?php foreach ($group as $k => $deviser) { ?>
						<div class="col-md-4 col-sm-4 col-xs-12 pad-showcase">
								<a href="<?= $deviser->getStoreLink()?>">
								<figure class="showcase">
									<img src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getHeaderBackgroundImage())->resize(350, 344) ?>" class="showcase-image">
								<figcaption>
								<div class="row">
									<div class="col-md-6">
										<div class="title-product-name sm align-left">
											<span><?= $deviser->getName() ?></span>
										</div>
										<div class="location align-left"><?= $deviser->personalInfoMapping->getCityLabel() ?></div>
									</div>
									<?php /*
									<div class="col-md-6">
										<button class="btn btn-icon mt-5"><i class="ion-ios-star-outline"></i><span>Follow</span>
									</button>
									</div>
									*/?>

								</div>
								</figcaption>
								</figure>
								</a>
							</div>
						<?php } ?>
					</div>
					<?php } ?>
					</div>
				</div>
				<?php if ($totalDevisers > 3) { ?>
					<a class="next" href="#carousel-devisers" role="button" data-slide="next">
						<i class="ion-ios-arrow-right"></i>
					</a>
				<?php } ?>
			</div>
		</div>
	</section>
	<!-- /SHOWCASE -->
	<?php if ($totalInfluencers) { ?>
	<section class="showcase-wrapper">
		<div class="container">
			<h3 class="title-product-name"><?=Yii::t('app/public', 'HOME_INFLUENCERS')?></h3>
			<span class="subtitle-home"><?=Yii::t('app/public', 'HOME_INFLUENCERS_TEXT')?></span>
			<!-- Controls -->
			<div class="carusel-container">
				<?php if ($totalInfluencers > 3) { ?>
					<a class="prev" href="#carousel-influencers" role="button" data-slide="prev">
						<i class="ion-ios-arrow-left"></i>
					</a>
				<?php } ?>
				<div class="carousel-devisers-container <?= $totalInfluencers > 3 ? 'carousel slide' : ''?>" id="carousel-influencers" data-ride="carousel" data-interval="false">
					<div class="<?= $totalInfluencers > 3 ? 'carousel-inner' : ''?>" role="listbox">
						<?php foreach ($influencers as $i => $group) { ?>
							<div class="item <?= ($i==0) ? 'active' : '' ?>">
								<?php foreach ($group as $k => $influencer) { ?>
									<div class="col-md-4 col-sm-4 col-xs-6 pad-showcase">
										<a href="<?= $influencer->getLovedLink()?>">
											<figure class="showcase">
												<img src="<?= Utils::url_scheme() ?><?= Utils::thumborize($influencer->getHeaderBackgroundImage())->resize(350, 344) ?>" class="showcase-image">
												<figcaption>
												<div class="row">
													<div class="col-md-6">
														<span class="title-product-name sm align-left"><?= $influencer->getName() ?></span>
														<span class="location align-left"><?= $influencer->personalInfoMapping->getCityLabel() ?></span>
													</div>
													<?php /*
													<div class="col-md-6">
														<button class="btn btn-icon mt-5"><i class="ion-ios-star-outline"></i><span>Follow</span></button>
													</div>
													*/ ?>
												</div>
												</figcaption>
											</figure>
										</a>
									</div>
								<?php } ?>
							</div>
						<?php } ?>
					</div>
				</div>
				<?php if ($totalInfluencers > 3) { ?>
					<a class="next" href="#carousel-influencers" role="button" data-slide="next">
						<i class="ion-ios-arrow-right"></i>
					</a>
				<?php } ?>
			</div>
		</div>
	</section>
<?php } ?>
<!-- GRID -->
<section class="showcase-wrapper">
		<div class="container">
			<h3 class="title-product-name"><?=Yii::t('app/public', 'HOME_BOXES')?></h3>
			<div class="boxes-container">
				<div class="row">
					<?php foreach ($boxes as $box) {
						$products = $box->getProductsPreview(); ?>
						<div class="col-md-3">
							<a href="<?= $box->getViewLink()?>">
								<figure class="showcase">
									<div class="images-box">
										<div class="bottom-top-images">
											<div class="image-left">
												<img src="<?=isset($products[0]) ? $products[0]['main_photo'] : 'imgs/img-default.jpg'?>" class="showcase-image">
											</div>
											<div class="image-right">
												<img src="<?=isset($products[1]) ? $products[1]['main_photo'] : 'imgs/img-default.jpg'?>" class="showcase-image">
											</div>
										</div>
										<div class="bottom-image">
											<img src="<?=isset($products[2]) ? $products[2]['main_photo'] : 'imgs/img-default.jpg'?>" class="showcase-image">
										</div>
									</div>
									<figcaption>
										<div class="row no-mar">
											<div class="col-md-8">
												<span class="boxes-text align-left"><?= \yii\helpers\StringHelper::truncate($box->name, 18, '…') ?></span>
											</div>
											<div class="col-md-4 no-padding">
												<button class="btn btn-single-love btn-love-box">
													<span class="number"><?=count($products)?></span>
													<span class="heart-icon"></span>
												</button>
											</div>
										</div>
									</figcaption>
								</figure>
							</a>
						</div>
					<?php } ?>

					<?php /*
					<div class="col-md-3">
							<a href="<?= $influencer->getLovedLink()?>">
									<figure class="showcase">
										<div class="images-box">
											<div class="bottom-top-images">
												<div class="image-left">
													<img src="imgs/img-default.jpg" class="showcase-image">
												</div>
												<div class="image-right">
													<img src="imgs/img-default.jpg" class="showcase-image">
												</div>
											</div>
											<div class="bottom-image">
												<img src="imgs/img-default.jpg" class="showcase-image">
											</div>
										</div>
										<figcaption>
										<div class="row">
											<div class="col-md-6">
												<span class="boxes-text align-left">Technology</span>
											</div>
											<div class="col-md-6">
												<button class="btn btn-single-love btn-love-box">
													<span class="number">18</span>
													<i class="ion-heart"></i>
												</button>
											</div>
										</div>
										</figcaption>
									</figure>
								</a>
						</div>
						<div class="col-md-3">
							<a href="<?= $influencer->getLovedLink()?>">
									<figure class="showcase">
										<div class="images-box">
											<div class="bottom-top-images">
												<div class="image-left">
													<img src="imgs/product-example.jpg" class="showcase-image">
												</div>
												<div class="image-right">
													<img src="imgs/product-example.jpg" class="showcase-image">
												</div>
											</div>
											<div class="bottom-image">
												<img src="imgs/product-example.jpg" class="showcase-image">
											</div>
										</div>
										<figcaption>
										<div class="row">
											<div class="col-md-6">
												<span class="boxes-text align-left">Vintage</span>
											</div>
											<div class="col-md-6">
												<button class="btn btn-single-love btn-love-box">
													<span class="number">180</span>
													<i class="ion-heart"></i>
												</button>
											</div>
										</div>
										</figcaption>
									</figure>
								</a>
						</div>
						<div class="col-md-3">
							<a href="<?= $influencer->getLovedLink()?>">
									<figure class="showcase">
										<div class="images-box">
											<div class="bottom-top-images">
												<div class="image-left">
													<img src="imgs/product-example.jpg" class="showcase-image">
												</div>
												<div class="image-right">
													<img src="imgs/product-example.jpg" class="showcase-image">
												</div>
											</div>
											<div class="bottom-image">
												<img src="imgs/product-example.jpg" class="showcase-image">
											</div>
										</div>
										<figcaption>
										<div class="row">
											<div class="col-md-6">
												<span class="boxes-text align-left">Leather</span>
											</div>
											<div class="col-md-6">
												<button class="btn btn-single-love btn-love-box">
													<span class="number">18</span>
													<i class="ion-heart"></i>
												</button>
											</div>
										</div>
										</figcaption>
									</figure>
								</a>
						</div>
						<div class="col-md-3">
							<a href="<?= $influencer->getLovedLink()?>">
									<figure class="showcase">
										<div class="images-box">
											<div class="bottom-top-images">
												<div class="image-left">
													<img src="imgs/img-default.jpg" class="showcase-image">
												</div>
												<div class="image-right">
													<img src="imgs/product-example.jpg" class="showcase-image">
												</div>
											</div>
											<div class="bottom-image">
												<img src="imgs/img-default.jpg" class="showcase-image">
											</div>
										</div>
										<figcaption>
										<div class="row">
											<div class="col-md-6">
												<span class="boxes-text align-left">Summer time</span>
											</div>
											<div class="col-md-6">
												<button class="btn btn-single-love btn-love-box">
													<span class="number">22</span>
													<i class="ion-heart"></i>
												</button>
											</div>
										</div>
										</figcaption>
									</figure>
								</a>
						</div>
						<div class="col-md-3">
							<a href="<?= $influencer->getLovedLink()?>">
									<figure class="showcase">
										<div class="images-box">
											<div class="bottom-top-images">
												<div class="image-left">
													<img src="imgs/img-default.jpg" class="showcase-image">
												</div>
												<div class="image-right">
													<img src="imgs/product-example.jpg" class="showcase-image">
												</div>
											</div>
											<div class="bottom-image">
												<img src="imgs/product-example.jpg" class="showcase-image">
											</div>
										</div>
										<figcaption>
										<div class="row">
											<div class="col-md-6">
												<span class="boxes-text align-left">Summer time</span>
											</div>
											<div class="col-md-6">
												<button class="btn btn-single-love btn-love-box">
													<span class="number">22</span>
													<i class="ion-heart"></i>
												</button>
											</div>
										</div>
										</figcaption>
									</figure>
								</a>
						</div>
						<div class="col-md-3">
							<a href="<?= $influencer->getLovedLink()?>">
									<figure class="showcase">
										<div class="images-box">
											<div class="bottom-top-images">
												<div class="image-left">
													<img src="imgs/img-default.jpg" class="showcase-image">
												</div>
												<div class="image-right">
													<img src="imgs/product-example.jpg" class="showcase-image">
												</div>
											</div>
											<div class="bottom-image">
												<img src="imgs/product-example.jpg" class="showcase-image">
											</div>
										</div>
										<figcaption>
										<div class="row">
											<div class="col-md-6">
												<span class="boxes-text align-left">Summer time</span>
											</div>
											<div class="col-md-6">
												<button class="btn btn-single-love btn-love-box">
													<span class="number">22</span>
													<i class="ion-heart"></i>
												</button>
											</div>
										</div>
										</figcaption>
									</figure>
								</a>
						</div>
						<div class="col-md-3">
							<a href="<?= $influencer->getLovedLink()?>">
									<figure class="showcase">
										<div class="images-box">
											<div class="bottom-top-images">
												<div class="image-left">
													<img src="imgs/img-default.jpg" class="showcase-image">
												</div>
												<div class="image-right">
													<img src="imgs/product-example.jpg" class="showcase-image">
												</div>
											</div>
											<div class="bottom-image">
												<img src="imgs/product-example.jpg" class="showcase-image">
											</div>
										</div>
										<figcaption>
										<div class="row">
											<div class="col-md-6">
												<span class="boxes-text align-left">Summer time</span>
											</div>
											<div class="col-md-6">
												<button class="btn btn-single-love btn-love-box">
													<span class="number">22</span>
													<i class="ion-heart"></i>
												</button>
											</div>
										</div>
										</figcaption>
									</figure>
								</a>
						</div>
						<div class="col-md-3">
							<a href="<?= $influencer->getLovedLink()?>">
									<figure class="showcase">
										<div class="images-box">
											<div class="bottom-top-images">
												<div class="image-left">
													<img src="imgs/img-default.jpg" class="showcase-image">
												</div>
												<div class="image-right">
													<img src="imgs/product-example.jpg" class="showcase-image">
												</div>
											</div>
											<div class="bottom-image">
												<img src="imgs/product-example.jpg" class="showcase-image">
											</div>
										</div>
										<figcaption>
										<div class="row">
											<div class="col-md-6">
												<span class="boxes-text align-left">Summer time</span>
											</div>
											<div class="col-md-6">
												<button class="btn btn-single-love btn-love-box">
													<span class="number">22</span>
													<i class="ion-heart"></i>
												</button>
											</div>
										</div>
										</figcaption>
									</figure>
								</a>
						</div>
						*/ ?>
				</div>
			</div>
		</div>
</section>
<?php /*
<section class="grid-wrapper">
	<div class="container">
		<div class="section-title">
			<span translate="todevise.index.HIGHLIGHTED_WORKS"></span>
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
 */ ?>
<!-- /GRID -->

<?php /*
<?php if ($boxes) { ?>
	<section class="grid-wrapper">
		<div class="container">

		<?php if ($stories) { ?>
			<div class="col-lg-8">
		<?php } ?>

				<div class="section-title">
					<span translate="global.BOXES"></span>
				</div>

				<div class="row no-mar">
					<?php foreach ($boxes as $box) {
						$products = $box->getProductsPreview(); ?>
						<div class="<?=$stories ? 'col-md-4' : 'col-md-4'?> col-xs-6 pad-grid">
							<div class="boxes-wrapper home">
								<?php if (empty($products)) { ?>
									<div class="empty-box">
										<span class="empty-title"><span translate="global.EMPTY_BOX"></span></span>
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
					<span translate="global.STORIES"></span>
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
*/?>


<!-- GRID -->
<section class="grid-wrapper" id="grid-product-home">
	<div class="container">
		<h3 class="title-product-name"><?=Yii::t('app/public', 'HOME_WORKS')?></h3>
		<div id="works-container" class="macy-container grid-margin" data-trueorder="true" data-waitforimages="true" data-columns="6">
			<?php foreach ($works as $i => $work) { ?>
			<div class="col-md-2">
				<div class="menu-category list-group">
					<div class="grid">
						<figure class="effect-zoe">
							<image-hover-buttons product-id="{{'<?= $work->short_id ?>'}}" is-loved="{{'<?=$work->isLovedByCurrentUser() ? 1 : 0 ?>'}}" is-mine="{{'<?= $work->isWorkFromCurrentUser() ? 1 : 0 ?>'}}">
								<a href="<?= $work->getViewLink()?>">
									<img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($work->getMainImage())->resize(400, 0) ?>">
									<span class="img-bgveil"></span>
								</a>
							</image-hover-buttons>
							<a href="<?= $work->getViewLink()?>">
								<figcaption>
									<p class="instauser">
										<?= \yii\helpers\StringHelper::truncate(Utils::l($work->name), 18, '…') ?>
									</p>
									<p class="price">€ <?= $work->getMinimumPrice() ?></p>
								</figcaption>
							</a>
						</figure>
					</div>	
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</section>


<!-- GRID -->
<?php /*
<section class="grid-wrapper">
	<div class="container">
		<div class="section-title">
			Highlighted Works
		</div>
*/?>
		<?php /*
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
 		*/ ?>
<?php /*
	</div>
</section>
*/ ?>
<!-- /GRID -->
