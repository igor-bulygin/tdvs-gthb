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

/** @var Person $deviser */

?>

<div class="banner-deviser">
	<div class="container pad-about">
		<img class="cover" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getHeaderBackgroundImage())->resize(1280, 450) ?>">
		<div class="banner-deviser-content">
			<div class="grey-overlay"></div>
			<div class="container">
				<div class="deviser-profile">
					<div class="avatar">
						<img class="cover" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getAvatarImage())->resize(250, 250) ?>">
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
				<nav class="menu-store" data-spy="affix" data-offset-top="435">
					<ul class="mt-0">
						<li>
							<a href="<?= Url::to(["deviser/store", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">Store</a>
							<!--<ul class="submenu-store">
								<li class="mt10">
									<a href="#">Ceramics</a>
								</li>
								<li>
									<a href="#">Sofas</a>
								</li>
								<li class="mb20">
									<a href="#">Paintings</a>
								</li>
							</ul>-->
						</li>
					</ul>
					<ul>
						<li>
							<a class="active" href="#">About</a>
						</li>
						<li>
							<a href="#">Press</a>
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
			<div class="col-md-10 about-bg">
				<div class="col-md-6 pad-about">
					<div class="about-wrapper">
						<div class="about-container">
							<div class="title">Abo<br>ut</div>
							<div class="name-location-wrapper">
								<div class="name">
									Anna Kovyneva
								</div>
								<div class="location">
									London, UK
								</div>
							</div>
							<div class="subtitle">
								Fashion Designer, Art Designer, Jewelry Designer, Technology Designer
							</div>
							<div class="resume-header">See resume</div>
							<p>I am a UX Designer and Art Director from Austria living in Berlin.</p>
							<p>Artworks and illustrations were my gateway to the creative industry which led to the foundation of my own studio and to first steps in the digital world.</p>
							<p>Out of this love for aesthetic design my passion for functionality and structure evolved. Jumping right into Photoshop didn’t feel accurate anymore and skipping the steps of building a framework based on functionality and usability became inevitable.</p>
						</div>
					</div>
				</div>
				<div class="col-md-6 pad-about about-grid">
					<div class="col-xs-12 pad-about item">
						<img class="grid-image" src="/imgs/photo-grid-about-1.jpg">
					</div>
					<div class="col-xs-6 pad-about item">
						<img class="grid-image" src="/imgs/photo-grid-about-2.jpg">
					</div>
					<div class="col-xs-6 pad-about item">
						<img class="grid-image" src="/imgs/photo-grid-about-3.jpg">
					</div>
					<div class="col-xs-12 pad-about item">
						<img class="grid-image" src="/imgs/photo-grid-about-4.jpg">
					</div>
					<div class="col-xs-6 pad-about item">
						<img class="grid-image" src="/imgs/photo-grid-about-5.jpg">
					</div>
					<div class="col-xs-6 pad-about item">
						<img class="grid-image" src="/imgs/photo-grid-about-6.jpg">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
