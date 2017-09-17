<?php

use app\assets\desktop\deviser\IndexStoryAsset;use app\components\PersonHeader;use app\components\PersonMenu;use app\models\Person;

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
							<a class="btn btn-red btn-create-story" href="<?=$person->getConnectWithInstagramLink()?>"><span translate="person.social.CONNECT_WITH_INSTAGRAM"></span></a>
						<?php } else { ?>
							<p class="no-video-text"><?=$person->getName()?> <span translate="person.social.NO_CONTENT_YET"></span></p>
						<?php } ?>
					</div>

				<?php } else { ?>

					<div class="store-grid">

						<div class="mesonry-row">

							<?php foreach ($photos['data'] as $index => $item) { ?>
								<div class="menu-category list-group">
									<div class="grid">
										<figure class="effect-zoe">
											<a href="#socialGallery" data-slide-to="<?= $index ?>" data-toggle="modal" data-target="#carouselModal">
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

<?php if ($photos && isset($photos['data'])) { ?>
	<div class="modal full-modal fade" id="carouselModal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-body">
					<button type="button" class="close" data-dismiss="modal" title="Close"><span class="ion-ios-close-empty"></span></button>
					<div id="socialGallery" class="carousel slide" data-interval="false">
						<div class="carousel-inner">
							<?php
							$active = true;
							foreach ($photos['data'] as $index => $item) { ?>
								<div class="item <?=$active ? 'active' : ''?>">
									<img src="<?=$item['images']['standard_resolution']['url']?>">
								</div>
								<?php
								$active = false;
							} ?>
						</div>
					</div>
					<a href="#socialGallery" class="left carousel-control" role="button" data-slide="prev"><i class="ion-ios-arrow-left"></i></a>
					<a href="#socialGallery" class="right carousel-control" role="button" data-slide="next"><i class="ion-ios-arrow-right"></i></a>
				</div>
			</div>
		</div>
	</div>
<?php } ?>