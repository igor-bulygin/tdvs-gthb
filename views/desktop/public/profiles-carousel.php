<?php

use app\models\Person;

/** @var Person[] $persons */
$totalPersons = count($persons);
$idCarousel = 'carousel-'.$id;
?>
<!-- carousel sm md lg -->
<div class="hidden-xs carusel-container"><!--1-->
	<?php if ($totalPersons > 4) { ?>
		<a class="prev" href="#<?=$idCarousel?>" role="button" data-slide="prev">
			<i class="ion-ios-arrow-left"></i>
		</a>
	<?php } ?>
		<div style="height: 320px" class="carousel-devisers-container <?= $totalPersons > 3 ? 'carousel slide' : ''?>" id="<?=$idCarousel?>" data-ride="carousel" data-interval="false"><!--2-->
			<div class="<?= $totalPersons > 4 ? 'carousel-inner' : ''?>" role="listbox"><!--3-->
				<?php foreach ($persons as $i => $person) { ?>
					<?php if ($i % 4 === 0) { ?>
						<div class="item <?= ($i==0) ? 'active' : '' ?>"><!--4-->
					<?php } ?>

					<div class="col-md-6 col-sm-6 pad-showcase">
						<?=\app\components\Person::widget(['person' => $person]) ?>
					</div>

					<?php if (($i+1) % 4 === 0 || $person == end($persons)) { ?>
						</div><!--4-->
					<?php } ?>
				<?php } ?>
			</div><!--3-->
		</div><!--2-->
	<?php if ($totalPersons > 4) { ?>
		<a class="next" href="#<?=$idCarousel?>" role="button" data-slide="next">
			<i class="ion-ios-arrow-right"></i>
		</a>
	<?php } ?>
</div><!--1-->
<!-- carousel xs -->
<div class="hidden-sm hidden-md hidden-lg carusel-container">
	<?php if ($totalPersons > 2) { ?>
		<a class="col-xs-0-5 prev" href="#<?=$idCarousel?>-xs" role="button" data-slide="prev">
			<i class="ion-ios-arrow-left"></i>
		</a>
	<?php } ?>
		<div style="height: 320px" class="col-xs-11 carousel-devisers-container <?= $totalPersons > 2 ? 'carousel slide' : ''?>" id="<?=$idCarousel?>-xs" data-ride="carousel" data-interval="false">
			<div class="<?= $totalPersons > 2 ? 'carousel-inner' : ''?>" role="listbox">
				<?php foreach ($persons as $i => $person) { ?>
					<?php if ($i % 2 === 0) { ?>
						<div class="item <?= ($i==0) ? 'active' : '' ?>"><!--4-->
					<?php } ?>

					<div class="col-xs-12 pad-showcase">
						<?=\app\components\Person::widget(['person' => $person]) ?>
					</div>

					<?php if (($i+1) % 2 === 0 || $person == end($persons)) { ?>
						</div><!--4-->
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	<?php if ($totalPersons > 2) { ?>
		<a class="col-xs-0-5 next" href="#<?=$idCarousel?>-xs" role="button" data-slide="next">
			<i class="ion-ios-arrow-right"></i>
		</a>
	<?php } ?>
</div>
