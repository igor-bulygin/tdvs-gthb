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

/** @var array $aboutImages */
$aboutImages = [];
foreach ($deviser->getAboutUrlImages() as $key => $urlImage) {
	// very specific instructions from todevise owner,
	// about how to show images, depending on how many images has to show
	switch ($key + 1) {
		case 1:
		case 7:
			$imageData = [
				"src" => $urlImage,
				"class" => "col-xs-12",
			];
			break;
		case 2:
			$imageData = [
				"src" => $urlImage,
				"class" => (count($deviser->getAboutUrlImages()) == 2) ? "col-xs-12" : "col-xs-6",
			];
			break;
		case 4:
			$imageData = [
				"src" => $urlImage,
				"class" => (in_array(count($deviser->getAboutUrlImages()), [4, 6, 7])) ? "col-xs-12" : "col-xs-6",
			];
			break;
		case 3:
		case 5:
		case 6:
			$imageData = [
				"src" => $urlImage,
				"class" => "col-xs-6",
			];
			break;
		default:
			$imageData = [
				"src" => $urlImage,
				"class" => "col-xs-6",
			];
			break;
	}
	$aboutImages[] = $imageData;
}

?>

<?= DeviserHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= DeviserMenu::widget() ?>
			</div>
			<div class="col-md-10 about-bg">
				<div class="col-md-5 pad-about">
					<div class="about-wrapper">
						<div class="about-container">
							<!--<div class="title">Abo<br>ut</div>-->
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
							<?php if ($deviser->hasResumeFile()) { ?>
							<div class="resume-header"><a href="<?= $deviser->getUrlResumeFile() ?>">See resume</a></div>
							<?php } ?>
							<div class="deviser-biography">
							    <p><?= $deviser->text_biography ?></p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-7 pad-about about-grid">
					<?php foreach ($aboutImages as $urlImage) { ?>
						<div class="<?= $urlImage["class"] ?> pad-about item">
							<img class="grid-image" src="<?= $urlImage["src"] ?>">
						</div>
					<?php } ?>
<!---->
<!--					<div class="col-xs-12 pad-about item">-->
<!--						<img class="grid-image" src="/imgs/photo-grid-about-1.jpg">-->
<!--					</div>-->
<!--					<div class="col-xs-6 pad-about item">-->
<!--						<img class="grid-image" src="/imgs/photo-grid-about-2.jpg">-->
<!--					</div>-->
				</div>
			</div>
		</div>
	</div>
</div>