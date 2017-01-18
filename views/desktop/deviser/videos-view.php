<?php
use app\assets\desktop\pub\PublicCommonAsset;
use app\components\DeviserHeader;
use app\components\DeviserMenu;
use app\models\Person;
use app\models\PersonVideo;
use yii\helpers\Url;

PublicCommonAsset::register($this);

/** @var Person $deviser */

$this->title = 'About ' . $deviser->personalInfoMapping->getBrandName() . ' - Todevise';
$this->params['deviser'] = $deviser;
$this->params['deviser_menu_active_option'] = 'videos';
$this->params['deviser_links_target'] = 'public_view';

/** @var $video PersonVideo */

?>

<?= DeviserHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= DeviserMenu::widget() ?>
			</div>
			<div class="col-md-10">
				<?php if (count($videos) == 0) { ?>
					<div class="empty-wrapper">
						<?php if ($deviser->isDeviserEditable()) { ?>
							<div><a class="red-link-btn" href="<?= Url::to(["deviser/videos-edit", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">Add / remove videos</a></div>
						<?php } ?>
						<img class="sad-face" src="/imgs/sad-face.svg">
						<p class="no-video-text">You have no videos</p>
					</div>
				<?php } else { ?>
				<div class="video-container">
					<?php if ($deviser->isDeviserEditable()) { ?>
						<div><a class="red-link-btn" href="<?= Url::to(["deviser/videos-edit", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">Add / remove videos</a></div>
					<?php } ?>
					<?php foreach ($videos as $video) { ?>
					<div class="col-sm-<?= (count($videos)<=3) ? '12' : '6' ?>">
						<div class="video-wrapper">
							<iframe width="560" height="315" src="<?= $video->getUrlEmbeddedYoutubePlayer() ?>" frameborder="0" allowfullscreen></iframe>
						</div>
					</div>
					<?php }  ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>