<?php

use app\helpers\Utils;

/* @var \app\models\Product[] $works */

?>

<?php foreach ($works as $i => $work) { ?>
	<div class="col-sm-4 col-md-2">
		<div class="menu-category list-group">
			<div class="grid">
				<figure class="effect-zoe">
					<image-hover-buttons product-id="{{'<?= $work->short_id ?>'}}" is-loved="{{'<?=$work->isLovedByCurrentUser() ? 1 : 0 ?>'}}" is-mine="{{'<?= $work->isWorkFromCurrentUser() ? 1 : 0 ?>'}}">
						<a href="<?= $work->getViewLink()?>">
							<img class="grid-image" src="<?= $work->getImagePreview(400, 500) ?>">
							<span class="img-bgveil"></span>
						</a>
					</image-hover-buttons>
					<a href="<?= $work->getViewLink()?>">
						<figcaption>
							<p class="instauser">
								<?= \yii\helpers\StringHelper::truncate(Utils::l($work->name), 18, '…') ?>
							</p>
							<p class="price">€ <?= $work->getMinimumPrice() ?></p>
						</figcaption>
					</a>
				</figure>
			</div>
		</div>
	</div>
<?php } ?>