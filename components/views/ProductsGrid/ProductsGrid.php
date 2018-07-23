<?php

use app\helpers\Utils;

/* @var \app\models\Product[] $products */

?>

<?php foreach ($products as $i => $product) { ?>
	<div class="<?=$css_class?>">
		<div class="menu-category list-group">
			<div class="grid">
				<figure class="effect-zoe">
					<image-hover-buttons product-id="{{'<?= $product->short_id ?>'}}" is-loved="{{'<?=$product->isLovedByCurrentUser() ? 1 : 0 ?>'}}" is-mine="{{'<?= $product->isWorkFromCurrentUser() ? 1 : 0 ?>'}}">
						<a href="<?= $product->getViewLink()?>">
							<img class="grid-image b-lazy"
								 src="<?=Utils::getDefaultImage(320, 400)?>"
								 data-src="<?= $product->getImagePreview(320, 400)	 ?>">
							<span class="img-bgveil"></span>
						</a>
					</image-hover-buttons>
					<a href="<?= $product->getViewLink()?>">
						<figcaption>
							<p class="instauser">
								<?= \yii\helpers\StringHelper::truncate(Utils::l($product->name), 18, '…') ?>
							</p>
							<p class="price">€ <?= $product->getMinimumPrice() ?></p>
						</figcaption>
					</a>
				</figure>
			</div>
		</div>
	</div>
<?php } ?>
