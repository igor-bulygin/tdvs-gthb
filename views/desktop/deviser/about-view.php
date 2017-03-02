<?php
use app\assets\desktop\pub\PublicCommonAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;
use yii\helpers\Url;

PublicCommonAsset::register($this);

/** @var Person $person */

$this->title = 'About ' . $person->personalInfoMapping->getBrandName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['deviser_menu_active_option'] = 'about';
$this->params['deviser_links_target'] = 'public_view';

/** @var array $aboutImages */
$aboutImages = [];
foreach ($person->getAboutUrlImages() as $key => $urlImage) {
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
				"class" => (count($person->getAboutUrlImages()) == 2) ? "col-xs-12" : "col-xs-6",
			];
			break;
		case 4:
			$imageData = [
				"src" => $urlImage,
				"class" => (in_array(count($person->getAboutUrlImages()), [4, 6, 7])) ? "col-xs-12" : "col-xs-6",
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
				<?= PersonMenu::widget() ?>
			</div>
			<div class="col-md-10 about-bg">
				<div class="col-md-5 pad-about">
					<div class="about-wrapper">
						<div class="about-container">
							<?php if ($person->isDeviserEditable()) { ?>
								<div><a class="red-link-btn" href="<?= Url::to(["deviser/about-edit", "slug" => $person->slug, 'deviser_id' => $person->short_id])?>">Edit about</a></div>
							<?php } ?>
							<!--<div class="title">Abo<br>ut</div>-->
							<div class="name-location-wrapper">
								<div class="name">
									<?= $person->personalInfoMapping->getBrandName() ?>
								</div>
								<div class="location">
									<?= $person->personalInfoMapping->getLocationLabel() ?>
								</div>
							</div>
							<div class="subtitle">
								<?= $person->getCategoriesLabel() ?>
							</div>
							<?php if ($person->hasResumeFile()) { ?>
							<div class="resume-header"><a href="<?= $person->getUrlResumeFile() ?>">See resume</a></div>
							<?php } ?>
							<div class="deviser-biography">
							    <p><?= $person->text_biography ?></p>
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
