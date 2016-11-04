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
					<div class="avatar-btn-profile">
						<div class="avatar">
							<img class="cover" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getAvatarImage())->resize(340, 340) ?>">
						</div>
						<div class="edit-profile-btn">
							<a class="btn btn-default btn-transparent btn-header" href="<?= Url::to(["deviser/about-edit", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">Edit profile</a>
						</div>
						<div class="deviser-data">
							<div class="name">
								<?= $deviser->personalInfo->getBrandName() ?>
							</div>
							<div class="location">
								<?= $deviser->personalInfo->getCityLabel() ?>
							</div>
							<div class="description">
								<?= $deviser->text_short_description ?>
							</div>
						</div>
					</div>
				</div>
					<a class="btn btn-default btn-green btn-add-work" href="<?= Url::to(["product/create", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">Add Work</a>
			</div>
		</div>
	</div>
</div>
