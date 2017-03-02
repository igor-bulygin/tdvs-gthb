<?php
use app\assets\desktop\pub\LovedViewAsset;
use app\components\DeviserMenu;
use app\components\PersonHeader;
use app\helpers\Utils;
use app\models\Person;

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

<?= PersonHeader::widget() ?>

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
                            <p>Start now by clicking the <span class="glyphicon glyphicon-heart"></span> button inside a work.</p>
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
                                        <div class="grid">
                                            <figure class="effect-zoe">
                                                <image-hover-buttons product-id="{{'<?= $product->short_id ?>'}}" is-loved="{{'<?=$product->isLovedByCurrentUser() ? 1 : 0 ?>'}}" is-mine="{{'<?= $product->isWorkFromCurrentUser() ? 1 : 0 ?>'}}">
                                                    <a href="<?= $product->getViewLink() ?>">
                                                        <img class="grid-image"
                                                             src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize(400, 0) ?>">
                                                    </a>
                                                </image-hover-buttons>
                                                <a href="<?= $product->getViewLink() ?>">
                                                    <figcaption>
                                                        <p class="instauser">
															<?= $product->name ?>
                                                        </p>
                                                        <p class="price">€ <?= $product->getMinimumPrice() ?></p>
                                                    </figcaption>
                                                </a>
                                            </figure>
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

