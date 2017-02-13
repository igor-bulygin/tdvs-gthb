<?php
use app\assets\desktop\pub\LovedViewAsset;
use app\components\DeviserHeader;
use app\components\DeviserMenu;
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Url;

LovedViewAsset::register($this);

/** @var Person $deviser */
/** @var \app\models\Loved[] $loveds */

$this->title = 'Loved by ' . $deviser->personalInfoMapping->getBrandName() . ' - Todevise';
$this->params['deviser'] = $deviser;
$this->params['deviser_menu_active_option'] = 'loved';
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
                <?php if (empty($loveds)) { ?>
                    <div class="empty-wrapper">
                        <?php if ($deviser->isConnectedUser()) { ?>
                            <p class="no-video-text">You haven't loved any works yet.</p>
                            <p>Start now by clicking the (icono loved) button inside a work.</p>
                        <?php } else { ?>
                            <p class="no-video-text"><?=$deviser->getBrandName()?> haven't loved any works yet.</p>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="content-store">
                        <div class="store-grid">
                            <div class="title-wrapper">
                                <span class="title">Loved by <?=$deviser->getBrandName()?></span>
                            </div>
                            <div id="macy-container">
                                <?php foreach ($loveds as $loved) {
                                    $product = $loved->getProduct();
                                    if ($product->product_state != \app\models\Product2::PRODUCT_STATE_ACTIVE) {
										continue;
									} ?>
                                    <div class="menu-category list-group">
                                        <a href="<?= Url::to(['product/detail', 'slug' => $product->slug, 'product_id' => $product->short_id])?>">
                                            <div class="grid">
                                                <figure class="effect-zoe">
                                                    <div image-hover-buttons>
                                                        <img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize(400, 0) ?>">
                                                    </div>
                                                    <figcaption>
                                                        <p class="instauser"><?= $product->name ?></p>
                                                        <p class="price">€ <?= $product->getMinimumPrice() ?></p>
                                                    </figcaption>
                                                </figure>
                                            </div>
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

