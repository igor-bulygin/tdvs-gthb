<?php

use app\assets\desktop\deviser\IndexStoryAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;

IndexStoryAsset::register($this);


/** @var Person $person */
/** @var array $photos */
$this->title = Yii::t('app/public',
	'SOCIAL_FEED_BY_PERSON_NAME',
	['person_name' => $person->getName()]
);
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'social';
$this->params['person_links_target'] = 'public_view';

?>

<?= PersonHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= PersonMenu::widget() ?>
			</div>
			<div class="col-md-10">
				<div class="content-store">
				 <?php if (!$connected) { ?>

					<div class="empty-wrapper">
						<?php if ($person->isPersonEditable()) { ?>
							<p class="no-story-text"><span translate="person.social.CONNECT_YOUR_PROFILE"></span></p>
							<a class="btn btn-green btn-create-story" href="<?=$person->getConnectWithInstagramLink()?>"><span translate="person.social.CONNECT_WITH_INSTAGRAM"></span></a>
						<?php } else { ?>
							<p class="no-video-text"><?=$person->getName()?> <span translate="person.social.NO_CONTENT_YET"></span></p>
						<?php } ?>
					</div>

				<?php } else { ?>

					<div class="store-grid">

						<div class="mesonry-row">

							<?php foreach ($photos['data'] as $item) { ?>
								<div class="menu-category list-group">
									<div class="grid">
										<figure class="effect-zoe">
											<a href="<?=$item['link']?>" target="_blank">
												<img src="<?=$item['images']['standard_resolution']['url']?>" />
											</a>
										</figure>
									</div>
								</div>

							 <?php } ?>

						</div>
					</div>

				<?php } ?>
			</div>
		</div>
	</div>
</div>