<?php

use app\assets\desktop\deviser\GlobalAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;
use app\models\PersonVideo;

GlobalAsset::register($this);

/** @var Person $person */
$this->title = Yii::t('app/public',
	'VIDEOS_BY_PERSON_NAME',
	['person_name' => $person->getName()]
);
Yii::$app->opengraph->title = $this->title;

$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'videos';
$this->params['person_links_target'] = 'public_view';

/** @var $video PersonVideo */

?>

<?= PersonHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= PersonMenu::widget() ?>
			</div>
			<div class="col-md-10">
				<?php if (count($videos) == 0) { ?>
					<div class="empty-wrapper">
						<?php if ($person->isPersonEditable()) { ?>
							<div><a class="red-link-btn" href="<?= $person->getVideosEditLink()?>"><span translate="person.videos.ADD_REMOVE_VIDEOS"></span></a></div>
						<?php } ?>
						<img class="sad-face" src="/imgs/sad-face.svg">
						<p class="no-video-text"><span translate="person.videos.NO_VIDEOS"></span></p>
					</div>
				<?php } else { ?>
				<div class="video-container">
					<?php if ($person->isPersonEditable()) { ?>
						<div><a class="red-link-btn" href="<?= $person->getVideosEditLink()?>"><span translate="person.videos.ADD_REMOVE_VIDEOS"></span></a></div>
					<?php } ?>
					<?php foreach ($videos as $video) { ?>
					<div class="col-sm-<?= (count($videos)<=3) ? '12' : '12' //was 12 and 6, changed just to avoid eliminating code...?>">
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