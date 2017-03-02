<?php
use app\assets\desktop\pub\PublicCommonAsset;
use app\components\DeviserMenu;
use app\components\PersonHeader;
use app\models\Person;
use yii\helpers\Url;

PublicCommonAsset::register($this);

/** @var Person $deviser */

$this->title = 'About ' . $deviser->personalInfoMapping->getBrandName() . ' - Todevise';
$this->params['deviser_menu_active_option'] = 'about';
$this->params['deviser_links_target'] = 'public_view';
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

<?= PersonHeader::widget() ?>

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
							<?php if ($deviser->isDeviserEditable()) { ?>
								<div><a class="red-link-btn" href="<?= Url::to(["deviser/about-edit", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">Edit about</a></div>
							<?php } ?>
							<!--<div class="title">Abo<br>ut</div>-->
							<div class="name-location-wrapper">
								<div class="name">
									<?= $deviser->personalInfoMapping->getBrandName() ?>
								</div>
								<div class="location">
									<?= $deviser->personalInfoMapping->getLocationLabel() ?>
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
				</div>
			</div>
		</div>
	</div>
</div>
