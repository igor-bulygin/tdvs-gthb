<?php
use app\assets\desktop\deviser\EditPressAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Url;

EditPressAsset::register($this);

/** @var Person $person */
/** @var array $press */

$this->title = 'About ' . $person->personalInfoMapping->getBrandName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['deviser_menu_active_option'] = 'press';
$this->params['deviser_links_target'] = 'public_view';

?>

<?= PersonHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= PersonMenu::widget() ?>
			</div>
			<div class="col-md-10">
				<?php if (count($press) == 0) { ?>
					<div class="empty-wrapper">
						<?php if ($person->isDeviserEditable()) { ?>
							<div><a href="<?= Url::to(["deviser/press-edit", "slug" => $person->slug, 'deviser_id' => $person->short_id])?>" class="red-link-btn">Add / remove photos</a></div>
						<?php } ?>
						<img class="sad-face" src="/imgs/sad-face.svg">
						<p class="no-video-text">You don't have any press images!</p>
					</div>
				<?php } else { ?>
					<div class="empty-wrapper">
						<?php if ($person->isDeviserEditable()) { ?>
							<div><a href="<?= Url::to(["deviser/press-edit", "slug" => $person->slug, 'deviser_id' => $person->short_id])?>" class="red-link-btn">Add / remove photos</a></div>
						<?php } ?>
						<div class="mesonry-row press-3">
							<?php foreach ($press as $item) { ?>
								<div class="menu-category list-group draggable-list">
									<img class="grid-image draggable-img" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($person->getUrlImagesLocation() . $item)->resize(355, 0) ?>">
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>