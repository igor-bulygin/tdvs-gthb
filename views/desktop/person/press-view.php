<?php

use app\assets\desktop\deviser\GlobalAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\helpers\Utils;
use app\models\Person;

GlobalAsset::register($this);

/** @var Person $person */
/** @var array $press */

$this->title = Yii::t('app/public',
	'Press by {person_name} - Todevise',
	['person_name' => $person->getName()]
);
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'press';
$this->params['person_links_target'] = 'public_view';

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
						<?php if ($person->isPersonEditable()) { ?>
							<div><a href="<?= $person->getPressEditLink()?>" class="red-link-btn"><span translate="ADD_REMOVE_PHOTOS"></span></a></div>
						<?php } ?>
						<img class="sad-face" src="/imgs/sad-face.svg">
						<p class="no-video-text"><span translate="NO_PRESS"></span></p>
					</div>
				<?php } else { ?>
					<div class="empty-wrapper">
						<?php if ($person->isPersonEditable()) { ?>
							<div><a href="<?= $person->getPressEditLink()?>" class="red-link-btn"><span translate="ADD_REMOVE_PHOTOS"></span></a></div>
						<?php } ?>
						<div class="mesonry-row press-3">
							<?php foreach ($press as $index=>$item) { ?>
								<div class="menu-category list-group draggable-list" data-toggle="modal" data-target="#carouselModal">
									<a href="#pressGallery" data-slide-to="<?= $index ?>">
										<img class="grid-image draggable-img" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($person->getUrlImagesLocation() . $item)->resize(355, 0) ?>">
									</a>
								</div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<div class="modal full-modal fade" id="carouselModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" title="Close"><span class="ion-ios-close-empty"></span></button>
				<div id="pressGallery" class="carousel slide" data-interval="false">
						<div class="carousel-inner">
					<?php
					$active = true;
					foreach($press as $modal_index=>$item) { ?>
						<div class="item <?=$active ? 'active' : ''?>">
							<img src="<?= Utils::url_scheme() ?><?= Utils::thumborize($person->getUrlImagesLocation() . $item)->resize(0, 0) ?>">
						</div>
					<?php
						$active = false;
					} ?>
						</div>
				</div>
				<a href="#pressGallery" class="left carousel-control" role="button" data-slide="prev"><i class="ion-ios-arrow-left"></i></a>
				<a href="#pressGallery" class="right carousel-control" role="button" data-slide="next"><i class="ion-ios-arrow-right"></i></a>
			</div>
		</div>
	</div>
</div>