<?php

use app\assets\desktop\pub\Index2Asset;
use app\models\Person;

Index2Asset::register($this);

$this->title = Yii::t('app/public', 'INDEX_TITLE');
Yii::$app->opengraph->title = $this->title;

/** @var Person[][] $devisers */
/** @var int $totalDevisers */
/** @var Person[][] $influencers */
/** @var int $totalInfluencers */
/** @var \app\models\Product[] $works */
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
					<a href="<?=Yii::$app->getUrlManager()->getHostInfo()?>/deviser/musa-bajo-el-arbol/0ca469a/store">
						<img src="/imgs/home_square_1.jpg" class="responsive-image">
					</a>
				</div>
				<div class="col-sm-4">
					<a href="<?=Yii::$app->getUrlManager()->getHostInfo()?>/deviser/coast-cycles/b818a0w/store">
						<img src="/imgs/home_square_2.jpg" class="responsive-image">
					</a>
				</div>
				<div class="col-sm-4">
					<a href="<?=Yii::$app->getUrlManager()->getHostInfo()?>/deviser/pilar-del-campo/aa1e7c8/store">
						<img src="/imgs/home_square_3.jpg" class="responsive-image">
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
								<figure class="showcase influencers">
									<img class="deviser-discover-img showcase-image" src="<?= $deviser->getHeaderSmallImage() ?>">
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
											<figure class="showcase influencers">
												<img class="deviser-discover-img showcase-image" src="<?= $influencer->getHeaderSmallImage() ?>">
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
			<span class="subtitle-home"><?=Yii::t('app/public', 'HOME_BOXES_TEXT')?></span>
			<div class="boxes-container">
				<div class="row">
					<?php foreach ($boxes as $box) {
						$products = $box->getProductsPreview(); ?>
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<a href="<?= $box->getViewLink()?>">
								<figure class="showcase">
									<div class="images-box">
										<div class="bottom-top-images">
											<div class="image-left">
												<img src="<?=isset($products[0]) ? $products[0]['main_photo_512'] : 'imgs/img-default.jpg'?>" class="showcase-image">
											</div>
											<div class="image-right">
												<img src="<?=isset($products[1]) ? $products[1]['main_photo_512'] : 'imgs/img-default.jpg'?>" class="showcase-image">
											</div>
										</div>
										<div class="bottom-image">
											<img src="<?=isset($products[2]) ? $products[2]['main_photo_512'] : 'imgs/img-default.jpg'?>" class="showcase-image">
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
				</div>
			</div>
		</div>
</section>
<!-- /GRID -->

<!-- GRID -->
<section class="grid-wrapper" id="grid-product-home">
	<div class="container">
		<h3 class="title-product-name"><?=Yii::t('app/public', 'HOME_WORKS')?></h3>
		<div id="works-container" class="grid-margin">
			<form id="formPagination">
				<input type="hidden" id="category_id" name="category_id" value="<?=$category_id?>" />
			</form>
			<?=$htmlWorks?>
		</div>
	</div>
	<div class="text-center mt-30">
		<button class="btn btn-small btn-black-line" type="button" id="btnMoreWorks"><?=Yii::t('app/public', 'SEE_MORE')?></button>
	</div>
</section>
