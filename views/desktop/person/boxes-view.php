<?php
use app\assets\desktop\deviser\GlobalAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;

GlobalAsset::register($this);

/** @var Person $person */
/** @var \app\models\Box[] $boxes */

$this->title = 'Boxes by ' . $person->getName() . ' - Todevise';
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
							<p class="no-video-text">You have no boxes!</p>

							<button class="btn btn-green btn-add-box" ng-click="viewBoxesCtrl.openCreateBoxModal()">ADD BOX</button>
						<?php } else { ?>
							<p class="no-video-text"><?=$person->getName()?> has no boxes.</p>
						<?php } ?>
					</div>

				<?php } else { ?>

					<div class="content-store">
						<div class="store-grid">
							<div class="title-wrapper-boxes title-wrapper">
								<span class="title">Boxes by <?=$person->getName()?></span>
							</div>
							<div class="row">
								<?php if ($person->isPersonEditable()) { ?>
									<div class="col-lg-4">
										<!--<button class="btn btn-default" ng-click="viewBoxesCtrl.openCreateBoxModal()">Add boxxxx</button>-->
										<div class="box-loader-wrapper" ng-click="viewBoxesCtrl.openCreateBoxModal()">
											<div class="plus-add-wrapper">
												<div class="plus-add">
													<span>+</span>
												</div>
												<div class="text">Add box</div>
											</div>
										</div>
									</div>
								<?php } ?>
								<?php foreach ($boxes as $box) {
									$products = $box->getProductsPreview(); ?>
									<div class="col-lg-4">
										<div class="boxes-wrapper">
										<?php if (empty($products)) { ?>
											<div class="empty-box">
												<span class="empty-title">Empty box</span>
											</div>
										<?php } else {
											$count  = 1;
											foreach ($products as $product) {
												if ($count > 3) {
													break;
												} ?>
												<a href="<?= $box->getViewLink()?>">
													<img class="grid-image" src="<?=$product['box_photo']?>">
												</a>
												<?php
												$count ++;
											} ?>
										<?php } ?>

										<a class="group-box-title" href="<?= $box->getViewLink() ?>">
											<span><?=$box->name?> (<?=count($products)?>)</span>
										</a>
										</div>
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

