<?php

use app\assets\desktop\deviser\GlobalAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;

GlobalAsset::register($this);

/** @var Person $person */
/** @var \app\models\Loved[] $loveds */

$this->title = Yii::t('app/public',
	'LOVED_BY_PERSON_NAME',
	['person_name' => $person->getName()]
);
Yii::$app->opengraph->title = $this->title;

$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'loved';
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
				<?php if (empty($loveds)) { ?>
					<div class="empty-wrapper">
						<?php if ($person->isConnectedUser()) { ?>
							<p class="no-video-text"><span translate="person.loved.NO_LOVED"></span></p>
							<p><span translate="person.loved.START_LOVED"></span></p>
						<?php } else { ?>
							<p class="no-video-text"><?=$person->getName()?> <span translate="person.loved.NO_LOVED_YET"></span></p>
						<?php } ?>

					</div>
				<?php } else { ?>
					<div class="content-store">
						<div class="store-grid">
							<div class="title-wrapper title-loved">
								<span class="title"><span translate="person.loved.LOVED_BY"></span> <?=$person->getName()?></span>
							</div>
							<div id="loved-container" class="macy-container" data-columns="6">
								<?php foreach ($loveds as $loved) {
									$product = $loved->getProduct();
									if ($product->product_state != \app\models\Product::PRODUCT_STATE_ACTIVE) {
										continue;
									} ?>
									<div class="menu-category list-group">
										<div class="grid">
											<figure class="effect-zoe">
												<image-hover-buttons product-id="{{'<?= $product->short_id ?>'}}" is-loved="{{'<?=$product->isLovedByCurrentUser() ? 1 : 0 ?>'}}" is-mine="{{'<?= $product->isWorkFromCurrentUser() ? 1 : 0 ?>'}}">
													<a href="<?= $product->getViewLink() ?>">
														<img class="grid-image"
															 src="<?= $product->getImagePreview(400, 0)?>">
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

