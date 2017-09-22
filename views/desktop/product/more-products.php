<?php
use app\helpers\Utils;

/* @var \app\models\Product[] $products */
/* @var \app\models\Product[][] $moreWork */

?>

<?php foreach ($products as $i => $work) { ?>
	<div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
		<div class="grid">
			<figure class="effect-zoe">
				<image-hover-buttons product-id="{{'<?= $work->short_id ?>'}}" is-loved="{{'<?=$work->isLovedByCurrentUser() ? 1 : 0 ?>'}}" is-mine="{{'<?= $work->isWorkFromCurrentUser() ? 1 : 0 ?>'}}">
					<a href="<?= $work->getViewLink()?>">
						<img class="grid-image"
							 src="<?= $work->getImagePreview(362, 450)?>">
					</a>
				</image-hover-buttons>
				<a href="<?= $work->getViewLink()?>">
					<figcaption>
						<p class="instauser">
							<?= $work->name ?>
						</p>
						<p class="price">€ <?= $work->getMinimumPrice() ?></p>
					</figcaption>
				</a>
			</figure>
		</div>
	</div>
<?php } ?>