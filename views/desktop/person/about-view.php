<?php

use app\assets\desktop\deviser\GlobalAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\helpers\Utils;
use app\models\Person;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = Yii::t('app/public',
	'ABOUT_PERSON_NAME',
	['person_name' => $person->getName()]
);
Yii::$app->opengraph->title = $this->title;

$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'about';
$this->params['person_links_target'] = 'public_view';

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
			<div class="col-md-10 about-bg no-pad">
				<div class="col-md-5 pad-about">
					<div class="about-wrapper">
						<div class="about-container">
							<?php if ($person->isPersonEditable()) { ?>
								<div><a class="red-link-btn top" href="<?= $person->getAboutEditLink()?>"><span translate="person.about.EDIT_ABOUT"></span></a></div>
							<?php } ?>
							<div class="subtitle">
								<?= $person->getCategoriesLabel() ?>
							</div>
							<?php if ($person->hasResumeFile()) { ?>
							<div class="resume-header"><a href="<?= $person->getUrlResumeFile() ?>"><span translate="person.about.SEE_RESUME"></span></a></div>
							<?php } ?>
<!-- SHOW MORE LESS BUTTON - part 1 -->
							<input id="read-more" type="checkbox" class="read-more-state" />
							<div id="deviser-biography-box">
								<div id="deviser-biography" class="deviser-biography">
<!-- // SHOW MORE LESS BUTTON - part 1 -->
								    <p><?= Utils::l($person->text_biography) ?></p>
								</div>
<!-- SHOW MORE LESS BUTTON - part 2 -->
							</div>
							<label id="read-more-btn" for="read-more" class="read-more-trigger"></label>
							<script>
		    					function ShowHideButton(){
		    						if (document.getElementById('deviser-biography').clientHeight > 750){
		            					document.getElementById('read-more-btn').style.display='inherit';
		    						}
								}
								document.getElementsByTagName('body')[0].onload = ShowHideButton;
							</script>
<!-- // SHOW MORE LESS BUTTON - part 2 -->
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
