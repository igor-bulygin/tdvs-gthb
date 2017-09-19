<?php

use app\assets\desktop\deviser\GlobalAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;

GlobalAsset::register($this);

/** @var Person $person */
/** @var \app\models\Box[] $boxes */

$this->title = Yii::t('app/public',
	'BOXES_BY_PERSON_NAME',
	['person_name' => $person->getName()]
);

$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'boxes';
$this->params['person_links_target'] = 'public_view';

?>

<?= PersonHeader::widget() ?>

<div class="store" ng-controller="viewBoxesCtrl as viewBoxesCtrl">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= PersonMenu::widget() ?>
			</div>
			<div class="col-md-10">
				<?php if (empty($boxes)) { ?>

					<div class="empty-wrapper">
						<?php if ($person->isConnectedUser()) { ?>
							<img class="sad-face" src="/imgs/sad-face.svg">
							<p class="no-video-text"><span translate="person.boxes.NO_BOXES"></span></p>

							<button class="btn btn-red btn-add-box" ng-click="viewBoxesCtrl.openCreateBoxModal()"><span translate="person.boxes.ADD_BOX"></span></button>
						<?php } else { ?>
							<p class="no-video-text"><?=$person->getName()?> <span translate="person.boxes.USER_NO_BOXES"></span></p>
						<?php } ?>
					</div>

				<?php } else { ?>

					<div class="content-store">
						<div class="store-grid">
							<div class="row">
								<?php if ($person->isPersonEditable()) { ?>
									<div class="col-lg-4">
										<!--<button class="btn btn-default" ng-click="viewBoxesCtrl.openCreateBoxModal()">Add boxxxx</button>-->
										<div class="box-loader-wrapper">								
											<button class="btn btn-red btn-big btn-red" ng-click="viewBoxesCtrl.openCreateBoxModal()">
												<span translate="person.boxes.ADD_BOX"></span>
											</button>
										</div>
									</div>
								<?php } ?>
								<?php foreach ($boxes as $box) {
									$products = $box->getProductsPreview(); ?>
									<div class="col-lg-4">
										<a href="<?= $box->getViewLink()?>">
								<figure class="showcase">
									<div class="images-box">
										<div class="bottom-top-images">
											<div class="image-left">
												<img src="<?=isset($products[0]) ? $products[0]['main_photo'] : 'imgs/img-default.jpg'?>" class="showcase-image">
											</div>
											<div class="image-right">
												<img src="<?=isset($products[1]) ? $products[1]['main_photo'] : 'imgs/img-default.jpg'?>" class="showcase-image">
											</div>
										</div>
										<div class="bottom-image">
											<img src="<?=isset($products[2]) ? $products[2]['main_photo'] : 'imgs/img-default.jpg'?>" class="showcase-image">
										</div>
									</div>
									<figcaption>
										<div class="row no-mar">
											<div class="col-md-8">
												<span class="boxes-text align-left"><?=$box->name?></span>
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

				<?php } ?>
			</div>
		</div>
	</div>
</div>

