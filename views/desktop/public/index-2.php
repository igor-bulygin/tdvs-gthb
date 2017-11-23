<?php

use app\assets\desktop\pub\Index2Asset;
use app\models\Banner;
use app\models\Person;

Index2Asset::register($this);

if ($category) {
	$this->title = $category->getName();
} else {
	$this->title = Yii::t('app/public', 'INDEX_TITLE');
}
Yii::$app->opengraph->title = $this->title;

/** @var Banner[] $banners */
/** @var Banner[] $homeBanners */
/** @var Person[] $devisers */
/** @var int $totalDevisers */
/** @var Person[] $influencers */
/** @var int $totalInfluencers */
/** @var \app\models\Product[] $works */
/** @var \app\models\Box[] $boxes */
/** @var \app\models\Story[] $stories */

?>

<!-- BANNER-->
<div class="bs-example" data-example-id="simple-carousel">
	<div class="carousel slide" id="carousel-example-generic" data-ride="carousel">
		<?php if(count($banners) > 1) { ?>
		<ol class="carousel-indicators">
			<?php foreach ($banners as $i => $banner) { ?>
				<li data-target="#carousel-example-generic" data-slide-to="<?= $i ?>" class="<?= $i == 0 ? 'active' : '' ?>"></li>
			<?php } ?>
		</ol>
		<?php } ?>
		<div class="carousel-inner home-carousel" role="listbox">
			<?php foreach ($banners as $i => $banner) { ?>

					<div class="item <?= $i == 0 ? 'active' : '' ?>">
					 	<a href="<?= !empty($banner->link) ? $banner->link : '#'?>">
							<img src="<?= $banner->getImageLinkTranslated() ?>" alt="<?= $banner->alt_text ?>" title="">
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

<?php if (empty($category_id)) { ?>
<!-- SUB-BANNER -->
<section class="sub-banner hidden-xs hidden-sm">
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
<?php } ?>

<?php if (empty($category_id)) { ?>
<!-- SEASON BANNERS -->
<section class="season-banners">
	<div class="container">
		<div class="row">
			<?php foreach ($homeBanners as $banner) { ?>
			<div class="col-sm-4">
				<a href="<?= !empty($banner->link) ? $banner->link : '#'?>">
					<img src="<?= $banner->getImageLinkTranslated() ?>" alt="<?= $banner->alt_text ?>" title="" class="responsive-image">
				</a>
			</div>
			<?php } ?>
		</div>
	</div>
</section>
<!-- /SEASON BANNERS -->
<?php } ?>

<?php if ($devisersCarousel) { ?>
<!-- SHOWCASE -->
<section class="showcase-wrapper">
	<div class="container">
		<h3 class="title-product-name"><?=Yii::t('app/public', 'HOME_DEVISERS')?></h3>
		<span class="subtitle-home"><?=Yii::t('app/public', 'HOME_DEVISERS_TEXT')?></span>
		<!-- Controls -->
		<?=$devisersCarousel?>
	</div>
</section>
<?php } ?>

<?php if ($influencersCarousel) { ?>
<!-- /SHOWCASE -->
<section class="showcase-wrapper">
	<div class="container">
		<h3 class="title-product-name"><?=Yii::t('app/public', 'HOME_INFLUENCERS')?></h3>
		<span class="subtitle-home"><?=Yii::t('app/public', 'HOME_INFLUENCERS_TEXT')?></span>
		<!-- Controls -->
		<?=$influencersCarousel?>
	</div>
</section>
<?php } ?>

<!-- GRID BOXES -->
<section class="showcase-wrapper">
	<div class="container">
		<h3 class="title-product-name"><?=Yii::t('app/public', 'HOME_BOXES')?></h3>
		<span class="subtitle-home"><?=Yii::t('app/public', 'HOME_BOXES_TEXT')?></span>
		<div class="boxes-container">
			<div class="row">
				<?php foreach ($boxes as $box) {
					$products = $box->getProductsPreview(); ?>
					<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 pad-showcase">
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
										<div class="col-xs-7 col-sm-7 col-md-8">
											<span class="boxes-text align-left"><?= \yii\helpers\StringHelper::truncate($box->name, 18, '…') ?></span>
										</div>
										<div class="col-xs-5 col-sm-5 col-md-4 no-padding">
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

<!-- GRID WORKS -->
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
