<?php
use app\components\DeviserHeader;
use app\components\DeviserMenu;
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

$this->title = 'About ' . $deviser->getBrandName() . ' - Todevise';
$this->params['deviser_menu_active_option'] = 'about';
$this->params['deviser'] = $deviser;

?>

<?= DeviserHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= DeviserMenu::widget() ?>
			</div>
			<div class="col-md-10 about-bg">
				<div class="col-md-6 pad-about">
					<div class="about-wrapper">
						<div class="about-container">
							<div class="title">Abo<br>ut</div>
							<div class="name-location-wrapper">
								<div class="name">
									<?= $deviser->getBrandName() ?>
								</div>
								<div class="location">
									<?= $deviser->getLocationLabel() ?>
								</div>
							</div>
							<div class="subtitle">
								<?= $deviser->getCategoriesLabel() ?>
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
