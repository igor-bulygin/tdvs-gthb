<?php

use app\assets\desktop\deviser\IndexStoryAsset;
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Json;

IndexStoryAsset::register($this);

/** @var Person $person */
/** @var \app\models\Story $story */

$this->title = Yii::t('app/public',
	'STORY_BY_PERSON_NAME',
	['story_title' => $story->getTitle(), 'person_name' => $person->getName()]
);
Yii::$app->opengraph->title = $this->title;

$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'stories';
$this->params['person_links_target'] = 'public_view';

$this->registerJs("var story = ".Json::encode($story), yii\web\View::POS_HEAD, 'story-var-script');
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>
<div class="our-devisers-wrapper" ng-controller="detailStoryCtrl as detailStoryCtrl">
	<div class="storie-detail-header">
		<div class="container relative">
			<div class="avatar-wrapper">
				<a href="<?= $person->getMainLink()?>">
					<img class="avatar-logued-user" src="<?= $person->getProfileImage(0, 0) ?>">
					<span class="person-name"><?=$person->getName()?></span>
				</a>
			</div>
			<?php if ($person->isPersonEditable()) { ?>
				<div class="pull-right">
					<?php /*s
					<button class="btn btn-default" type="button">
						<i class="ion-ios-heart"></i>
						Love Story
					</button>
					*/ ?>
				
					<a class="btn btn-default" href="<?=$story->getEditLink()?>">
						<span translate="person.stories.EDIT_STORY"></span>
					</a>

					<button class="btn btn-default" type="button" ng-click="detailStoryCtrl.openModal()">
						<i class="ion-delete"></i>
						<span translate="person.stories.DELETE_STORY"></span>
					</button>
				</div>


				<script type="text/ng-template" id="deleteStoryModal.html">
					<div class="modal-header">
						<h3 class="modal-title"><span translate="person.stories.DELETE_STORY_CONFIRMATION"></span></h3>
					</div>
					<div class="modal-body">
						<p><span translate="person.stories.DELETE_STORY_RECOVER"></span></p>
					</div>
					<div class="modal-footer">
						<button class="btn btn-default" ng-click="deleteStoryModalCtrl.ok()"><span translate="global.DELETE"></span></button>
						<button class="btn btn-default btn-red" ng-click="deleteStoryModalCtrl.cancel()"><span translate="global.CANCEL"></span></button>
					</div>
				</script>
			<?php } ?>

		</div>
	</div>
	<div class="stories-detail-content container">
		<div class="detail-title"><?= $story->getTitle() ?></div>
		<div class="detail-subtitle">
		<div>
			<div class="loved-comments-wrapper">
				<div class="loved-wrapper">
					<i class="ion-ios-heart"></i>
					<span>1.524</span>
				</div>
				<div class="comments-wrapper">
					<i class="ion-chatbox"></i>
					<span>82</span>
				</div>
			</div>
		</div>

		<?php if ($story->published_at) { ?>
			<div class="published-text"><span translate="person.stories.PUBLISHED_ON"></span> <?=$story->getPublishingDateFormatted()?></div>
		<?php } ?>
		</div>
		
		<div class="cover-photo">
			<img src="<?= $story->getMainPhotoUrl() ?>">
		</div>
		
		<?php foreach ($story->componentsMapping as $component) { ?>

			<?php if ($component->type == \app\models\StoryComponent::STORY_COMPONENT_TYPE_TEXT) { ?>

				<div class="row component-text">
					<div class="col-lg-12">
						<?=\app\helpers\Utils::l($component->items); ?>
					</div>
				</div>

			<?php } ?>


			<?php if ($component->type == \app\models\StoryComponent::STORY_COMPONENT_TYPE_PHOTOS) { ?>

				<div class="row component-photos">
					<?php foreach ($component->items as $photo) { ?>
						<div class="col-lg-3">
							<img src="<?=$person->getUrlImagesLocation().$photo['photo']?>" class="img-responsive" />
						</div>
					<?php } ?>
				</div>

			<?php } ?>


			<?php if ($component->type == \app\models\StoryComponent::STORY_COMPONENT_TYPE_VIDEOS) { ?>

				<div class="component-video row">
					<?php foreach ($component->items as $video) { ?>
						<div>
							<iframe height="315" src="<?= Utils::getUrlEmbeddedYoutubePlayer($video) ?>" frameborder="0" allowfullscreen></iframe>
						</div>
					<?php } ?>
				</div>

			<?php } ?>


			<?php if ($component->type == \app\models\StoryComponent::STORY_COMPONENT_TYPE_WORKS) { ?>

				<div class="row component-photos">
					<?php foreach ($component->items as $workId) {
						$work = \app\models\Product::findOneSerialized($workId['work']); ?>
						<div class="col-lg-3">
							<div class="grid">
								<figure class="effect-zoe">
									<image-hover-buttons product-id="{{'<?= $work->short_id ?>'}}" is-loved="{{'<?=$work->isLovedByCurrentUser() ? 1 : 0 ?>'}}" is-mine="{{'<?= $work->isWorkFromCurrentUser() ? 1 : 0 ?>'}}">
										<a href="<?= $work->getViewLink()?>">
											<img class="grid-image" src="<?= $work->getImagePreview(362, 450)?>">
										</a>
									</image-hover-buttons>
									<a href="<?= $work->getViewLink()?>">
										<figcaption>
											<p class="instauser"><?=$work->name?></p>
											<p class="price">€ <?= $work->getMinimumPrice() ?></p>
										</figcaption>
									</a>
								</figure>
							</div>
						</div>
					<?php } ?>
				</div>

			<?php } ?>

		<?php } ?>

	</div>
</div>

