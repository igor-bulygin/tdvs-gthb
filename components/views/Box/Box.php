<?php

/** @var \app\models\Box $box */

use yii\helpers\StringHelper;

$products = $box->getProductsPreview(); ?>

<a href="<?= $box->getViewLink()?>">
	<figure class="showcase">
		<div class="images-box">
			<div class="bottom-top-images">
				<div class="image-left">
					<img src="<?=isset($products[0]) ? $products[0]['main_photo_512'] : 'imgs/img-default.jpg'?>" class="showcase-image">
				</div>
				<div class="image-right">
					<img src="<?=isset($products[1]) ? $products[1]['main_photo_512'] : 'imgs/img-default.jpg'?>" class="showcase-image">
				</div>
			</div>
			<div class="bottom-image">
				<img src="<?=isset($products[2]) ? $products[2]['main_photo_512'] : 'imgs/img-default.jpg'?>" class="showcase-image">
			</div>
		</div>
		<figcaption>
			<div class="row no-mar">
				<div class="col-xs-7 col-sm-7 col-md-8">
					<span class="boxes-text align-left"><?= StringHelper::truncate($box->name, 18, '…') ?></span>
				</div>
				<div class="col-xs-5 col-sm-5 col-md-4 no-padding">
					<button class="btn btn-single-love btn-love-box">
						<span class="number"><?=count($products)?></span>
						<span class="heart-icon"></span>
					</button>
				</div>
			</div>
		</figcaption>
	</figure>
</a>