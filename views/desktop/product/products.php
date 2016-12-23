<?php
use app\helpers\Utils;
use yii\helpers\Url;

/* @var \app\models\Product2[] $products */
/* @var \app\models\Product2[][] $moreWork */
$this->title = 'Works - Todevise';

\app\assets\desktop\pub\ProductsAsset::register($this);

?>


<section class="grid-wrapper">
	<div class="container">
		<p class="text-primary"><?=$total?> results <?=($text ? 'of <b>'.$text.'</b>' : '')?></p>
		<div id="macy-container">
			<?php foreach ($products as $i => $work) { ?>
				<div>
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
		</div>
	</div>
</section>