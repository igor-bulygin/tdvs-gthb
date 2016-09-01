<?php
use app\components\assets\cropAsset;
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Url;

// use params to share data between views :(
/** @var Person $deviser */
$deviser = $this->params['deviser'];

?>

<div class="banner-deviser">
	<div class="container pad-about">
		<img class="cover" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getHeaderBackgroundImage())->resize(1280, 450) ?>">
		<div class="banner-deviser-content">
			<div class="grey-overlay"></div>
			<div class="container">
				<div class="deviser-profile">
					<div class="avatar">
						<img class="cover" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getAvatarImage())->resize(340, 340) ?>">
					</div>
					<div class="deviser-data">
						<div class="name">
							<?= $deviser->getBrandName() ?>
						</div>
						<div class="location">
							<?= $deviser->getCityLabel() ?>
						</div>
						<div class="description">
							<?= $deviser->getShortDescription() ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
