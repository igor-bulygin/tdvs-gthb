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
Yii::$app->opengraph->title = $this->title;

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
							<div class="_row">
								<?php if ($person->isPersonEditable()) { ?>
									<div class="col-lg-4">
										<!--<button class="btn btn-default" ng-click="viewBoxesCtrl.openCreateBoxModal()">Add boxxxx</button>-->
										<div class="box-loader-wrapper">
											<button id="btn_create_box" class="btn btn-red btn-default" ng-click="viewBoxesCtrl.openCreateBoxModal()">
												<span translate="person.boxes.ADD_BOX"></span>
											</button>
										</div>
									</div>
								<?php } ?>
								<?php foreach ($boxes as $box) {
									$products = $box->getProductsPreview(); ?>
									<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
										<?=\app\components\Box::widget(['box' => $box]) ?>
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

