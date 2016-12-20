<?php
use app\helpers\Utils;
use yii\helpers\Url;

/* @var \app\models\Product2[][] $moreWork */
$this->title = 'Works - Todevise';

?>


<section class="grid-wrapper">
	<p><?=$total?> results <?=($text ? 'of <b>'.$text.'</b>' : '')?></p>
	<div class="container">
		<div>
			<?php foreach ($moreWork as $worksGroup) { ?>
				<?php foreach ($worksGroup["twelve"] as $i => $work) { ?>
					<div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
						<a href="<?= Url::to(["product/detail", "slug" => Utils::l($work->slug), 'product_id' => $work->short_id])?>">
							<div class="grid">
								<figure class="effect-zoe">
									<img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($work->getMainImage())->resize(362, 450) ?>">
									<figcaption>
										<p class="instauser">
											<?= Utils::l($work->name) ?>
										</p>
										<p class="price">€ <?= $work->getMinimumPrice() ?></p>
									</figcaption>
								</figure>
							</div>
						</a>
					</div>
				<?php } ?>

				<?php foreach ($worksGroup["three"] as $i => $work) { ?>
					<div class="col-md-4 col-sm-4 pad-grid pad-grid-h">
						<a href="<?= Url::to(["product/detail", "slug" => Utils::l($work->slug), 'product_id' => $work->short_id])?>">
							<div class="grid">
								<figure class="effect-zoe">
									<img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($work->getMainImage())->resize(375, 220) ?>">
									<figcaption>
										<p class="instauser">
											<?= Utils::l($work->name) ?>
										</p>
										<p class="price">€ <?= $work->getMinimumPrice() ?></p>
									</figcaption>
								</figure>
							</div>
						</a>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</section>