<?php

use app\models\Person;

/** @var Person[] $persons */
$totalPersons = count($persons);
$idCarousel = 'carousel-'.$id;
?>
<!-- carousel md lg -->
<div class="hidden-xs hidden-sm carusel-container"><!--1-->
	<?php if ($totalPersons > 3) { ?>
		<a class="prev" href="#<?=$idCarousel?>" role="button" data-slide="prev">
			<i class="ion-ios-arrow-left"></i>
		</a>
	<?php } ?>
		<div class="carousel-devisers-container <?= $totalPersons > 3 ? 'carousel slide' : ''?>" id="<?=$idCarousel?>" data-ride="carousel" data-interval="false"><!--2-->
			<div class="<?= $totalPersons > 3 ? 'carousel-inner' : ''?>" role="listbox"><!--3-->
				<?php foreach ($persons as $i => $person) { ?>
					<?php if ($i % 3 === 0) { ?>
						<div class="item <?= ($i==0) ? 'active' : '' ?>"><!--4-->
					<?php } ?>

					<div class="col-md-4 col-sm-4 col-xs-12 pad-showcase">
						<?=\app\components\Person::widget(['person' => $person]) ?>
					</div>

					<?php if (($i+1) % 3 === 0 || $person == end($persons)) { ?>
						</div><!--4-->
					<?php } ?>
				<?php } ?>
			</div><!--3-->
		</div><!--2-->
	<?php if ($totalPersons > 3) { ?>
		<a class="next" href="#<?=$idCarousel?>" role="button" data-slide="next">
			<i class="ion-ios-arrow-right"></i>
		</a>
	<?php } ?>
</div><!--1-->
<!-- carousel sm -->
<div class="hidden-xs hidden-md hidden-lg carusel-container">
	<?php if ($totalPersons > 2) { ?>
		<a class="col-xs-0-5 prev" href="#<?=$idCarousel?>-sm" role="button" data-slide="prev">
			<i class="ion-ios-arrow-left"></i>
		</a>
	<?php } ?>
		<div class="col-xs-11 carousel-devisers-container <?= $totalPersons > 2 ? 'carousel slide' : ''?>" id="<?=$idCarousel?>-sm" data-ride="carousel" data-interval="false">
			<div class="<?= $totalPersons > 2 ? 'carousel-inner' : ''?>" role="listbox">
				<?php foreach ($persons as $i => $person) { ?>
					<?php if ($i % 2 === 0) { ?>
						<div class="item <?= ($i==0) ? 'active' : '' ?>">
					<?php } ?>

					<div class="col-sm-6 col-xs-12 pad-showcase">
						<?=\app\components\Person::widget(['person' => $person]) ?>
					</div>

					<?php if (($i+1) % 2 === 0 || $person == end($persons)) { ?>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	<?php if ($totalPersons > 2) { ?>
		<a class="col-xs-0-5 next" href="#<?=$idCarousel?>-sm" role="button" data-slide="next">
			<i class="ion-ios-arrow-right"></i>
		</a>
	<?php } ?>
</div>
<!-- carousel xs -->
<div class="hidden-sm hidden-md hidden-lg carusel-container">
	<?php if ($totalPersons > 1) { ?>
		<a class="col-xs-0-5 prev" href="#<?=$idCarousel?>-xs" role="button" data-slide="prev">
			<i class="ion-ios-arrow-left"></i>
		</a>
	<?php } ?>
		<div class="col-xs-11 carousel-devisers-container <?= $totalPersons > 1 ? 'carousel slide' : ''?>" id="<?=$idCarousel?>-xs" data-ride="carousel" data-interval="false">
			<div class="<?= $totalPersons > 1 ? 'carousel-inner' : ''?>" role="listbox">
				<?php foreach ($persons as $i => $person) { ?>
					<div class="item <?= ($i==0) ? 'active' : '' ?>">
						<div class="col-sm-6 col-xs-12 pad-showcase">
							<?=\app\components\Person::widget(['person' => $person]) ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php if ($totalPersons > 1) { ?>
		<a class="col-xs-0-5 next" href="#<?=$idCarousel?>-xs" role="button" data-slide="next">
			<i class="ion-ios-arrow-right"></i>
		</a>
	<?php } ?>
</div>
