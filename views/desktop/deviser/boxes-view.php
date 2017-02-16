<?php
use app\assets\desktop\pub\LovedViewAsset;
use app\components\DeviserHeader;
use app\components\DeviserMenu;
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Url;

LovedViewAsset::register($this);

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

<div class="store">
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
                            <button class="btn btn-default btn-green btn-upload-file">ADD BOX</button>
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
                                <div class="col-lg-4">
                                    <button>Add box</button>
                                </div>
                            <?php foreach ($boxes as $box) {
							    $products = $box->getProducts(); ?>
                                <div class="col-lg-4">
                                    <?php if (empty($products)) { ?>
                                        <div>Empty box</div>
                                    <?php } else {
                                        $count = 0;
                                        foreach ($products as $product) {
                                            if ($product->product_state != \app\models\Product2::PRODUCT_STATE_ACTIVE || $count > 3) {
                                                continue;
                                            }
                                            $count++;
                                            if ($count == 1) { ?>
                                                <a href="<?= Url::to(["product/detail", "slug" => $product->slug, 'product_id' => $product->short_id])?>">
                                                    <img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize(147, 114) ?>">
                                                </a>
                                            <?php } elseif ($count == 2) { ?>
                                                <a href="<?= Url::to(["product/detail", "slug" => $product->slug, 'product_id' => $product->short_id])?>">
                                                    <img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize(147, 114) ?>">
                                                </a>
                                            <?php } elseif ($count == 3) { ?>
                                                <a href="<?= Url::to(["product/detail", "slug" => $product->slug, 'product_id' => $product->short_id])?>">
                                                    <img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize(303, 280) ?>">
                                                </a>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>

                                    <span>
                                        <?=$box->name?> (<?=count($products)?>)
                                    </span>

                                </div>
                            <?php } ?>
                        </div>
                    </div>

                <?php } ?>
            </div>
		</div>
	</div>
</div>

