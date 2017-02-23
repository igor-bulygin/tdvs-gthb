<?php
use app\assets\desktop\pub\BoxesViewAsset;
use app\components\DeviserHeader;
use app\components\DeviserMenu;
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Url;

BoxesViewAsset::register($this);

/** @var Person $deviser */
/** @var \app\models\Box[] $boxes */

$this->title = 'Boxes by ' . $deviser->personalInfoMapping->getBrandName() . ' - Todevise';
$this->params['deviser'] = $deviser;
$this->params['deviser_menu_active_option'] = 'boxes';
$this->params['deviser_links_target'] = 'public_view';

/** array $faq */

// <a class="edit-faq-btn" href="<***?= Url::to(["deviser/faq-edit", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?****>">+ ADD / EDIT QUESTIONS</a>


?>

<?= DeviserHeader::widget() ?>

<div class="store" ng-controller="viewBoxesCtrl as viewBoxesCtrl">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= DeviserMenu::widget() ?>
			</div>
			<div class="col-md-10">
                <?php if (empty($boxes)) { ?>

                    <div class="empty-wrapper">
                        <?php if ($deviser->isConnectedUser()) { ?>
                            <img class="sad-face" src="/imgs/sad-face.svg">
                            <p class="no-video-text">You have no boxes!</p>
                            <button class="btn btn-default btn-green btn-upload-file" ng-click="viewBoxesCtrl.openCreateBoxModal()">ADD BOX</button>
						<?php } else { ?>
                            <p class="no-video-text"><?=$deviser->getBrandName()?> have no boxes.</p>
                        <?php } ?>
                    </div>

                <?php } else { ?>

                    <div class="content-store">
                        <div class="store-grid">
                            <div class="title-wrapper">
                                <span class="title">Boxes by <?=$deviser->getBrandName()?></span>
                            </div>
                            <div class="row">
                                <?php if ($deviser->isDeviserEditable()) { ?>
                                    <div class="col-lg-4">
                                        <button class="btn btn-default" ng-click="viewBoxesCtrl.openCreateBoxModal()">Add box</button>
                                    </div>
                                <?php } ?>
                                <?php foreach ($boxes as $box) {
                                    $products = $box->getProducts(); ?>
                                    <div class="col-lg-4">
                                        <?php if (empty($products)) { ?>
                                            <div>Empty box</div>
                                        <?php } else {
                                            $sizes = [
                                                1 => [
                                                    [273, 373],
                                                ],
                                                2 => [
                                                    [273, 105],
                                                    [273, 257],
                                                ],
                                                3 => [
                                                    [132, 105],
                                                    [132, 105],
                                                    [273, 257],
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

                                        <a href="<?= Url::to(["box-detail", "slug" => $deviser->slug, "deviser_id" => $deviser->short_id, "box_id" => $box->short_id])?>"><?=$box->name?> (<?=count($products)?>)</a>

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
