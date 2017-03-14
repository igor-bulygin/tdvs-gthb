<?php
use app\assets\desktop\pub\BoxesViewAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\helpers\Utils;
use app\models\Person;

BoxesViewAsset::register($this);

/** @var Person $person */
/** @var \app\models\Box[] $boxes */

$this->title = 'Boxes by ' . $person->personalInfoMapping->getBrandName() . ' - Todevise';
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
                            <p class="no-video-text"><?=$person->getBrandName()?> have no boxes.</p>
                        <?php } ?>
                    </div>

                <?php } else { ?>

                    <div class="content-store">
                        <div class="store-grid">
                            <div class="title-wrapper-boxes title-wrapper">
                                <span class="title">Boxes by <?=$person->getBrandName()?></span>
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
                                    $products = $box->getProducts(); ?>
                                    <div class="col-lg-4">
                                        <div class="boxes-wrapper">
                                        <?php if (empty($products)) { ?>
                                            <div class="empty-box">
                                            	<span class="empty-title">Empty box</span>
                                            </div>
                                        <?php } else {
                                            $sizes = [
                                                1 => [
                                                    [295, 372],
                                                ],
                                                2 => [
                                                    [295, 115],
                                                    [295, 257],
                                                ],
                                                3 => [
                                                    [146, 116],
                                                    [145, 116],
                                                    [295, 257],
                                                ],
                                            ];
                                            if (count($products) >= 3) {
                                                $size = $sizes[3];
                                            } elseif (count($products) == 2) {
                                                $size = $sizes[2];
                                            } else {
                                                $size = $sizes[1];
                                            }
                                            $count = 0;
                                            foreach ($products as $product) {
                                                if ($product->product_state != \app\models\Product2::PRODUCT_STATE_ACTIVE || $count > 3) {
                                                    continue;
                                                }
                                                $count++;
                                                if ($count == 1) { ?>
                                                    <a href="<?= $product->getViewLink()?>">
                                                        <img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize($size[0][0], $size[0][1]) ?>">
                                                    </a>
                                                <?php } elseif ($count == 2) { ?>
                                                    <a href="<?= $product->getViewLink()?>">
                                                        <img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize($size[1][0], $size[1][1]) ?>">
                                                    </a>
                                                <?php } elseif ($count == 3) { ?>
                                                    <a href="<?= $product->getViewLink()?>">
                                                        <img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize($size[2][0], $size[2][1]) ?>">
                                                    </a>
                                                <?php } ?>
                                            <?php } ?>
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

