<?php
use app\helpers\Utils;
use yii\helpers\Url;

/* @var \app\models\Product2[] $products */
/* @var \app\models\Product2[][] $moreWork */

?>

<?php foreach ($products as $i => $work) { ?>
	<div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
		<a href="<?= Url::to(["product/detail", "slug" => $work->slug, 'product_id' => $work->short_id])?>">
			<div class="grid">
				<figure class="effect-zoe">
					<img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($work->getMainImage())->resize(400, 0) ?>">
					<figcaption>
						<p class="instauser">
							<?= $work->name ?>
						</p>
						<p class="price">€ <?= $work->getMinimumPrice() ?></p>
					</figcaption>
				</figure>
			</div>
		</a>
	</div>
<?php } ?>