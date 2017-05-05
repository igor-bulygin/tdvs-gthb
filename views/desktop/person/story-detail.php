<?php
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Json;

app\assets\desktop\pub\StoryDetailAsset::register($this);

/** @var Person $person */
/** @var \app\models\Story $story */

$this->title = 'Story '.$story->getTitle().' by ' . $person->getName() . ' - Todevise';
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
					<img class="avatar-logued-user" src="<?= $person->getAvatarImage() ?>">
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
						Edit Story
					</a>

					<button class="btn btn-default" type="button" ng-click="detailStoryCtrl.openModal()">
						<i class="ion-delete"></i>
						Delete Story
					</button>
				</div>


				<script type="text/ng-template" id="deleteStoryModal.html">
					<div class="modal-header">
						<h3 class="modal-title">Are you sure you want to delete this story?</h3>
					</div>
					<div class="modal-body">
						<p>You will not be able to recover it.</p>
					</div>
					<div class="modal-footer">
						<button class="btn btn-default" ng-click="deleteStoryModalCtrl.ok()">Delete</button>
						<button class="btn btn-default btn-green" ng-click="deleteStoryModalCtrl.cancel()">Cancel</button>
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
			<div class="published-text">Published on <?=$story->getPublishingDateFormatted()?></div>
		<?php } ?>
		</div>
		
		<div class="cover-photo">
			<img src="<?= $story->getMainPhotoUrl() ?>">
		</div>
		
		<?php foreach ($story->componentsMapping as $component) { ?>

			<?php if ($component->type == \app\models\StoryComponent::STORY_COMPONENT_TYPE_TEXT) { ?>

				<div class="row">
					<div class="col-lg-12">
						<?=\app\helpers\Utils::l($component->items); ?>
					</div>
				</div>

			<?php } ?>


			<?php if ($component->type == \app\models\StoryComponent::STORY_COMPONENT_TYPE_PHOTOS) { ?>

				<div class="row">
					<?php foreach ($component->items as $photo) { ?>
						<div class="col-lg-3">
							<img src="<?=$person->getUrlImagesLocation().$photo['photo']?>" class="img-responsive" />
						</div>
					<?php } ?>
				</div>

			<?php } ?>


			<?php if ($component->type == \app\models\StoryComponent::STORY_COMPONENT_TYPE_VIDEOS) { ?>

				<div class="row">
					<?php foreach ($component->items as $video) { ?>
						<div class="col-lg-3">
							<iframe height="315" src="<?= Utils::getUrlEmbeddedYoutubePlayer($video) ?>" frameborder="0" allowfullscreen></iframe>
						</div>
					<?php } ?>
				</div>

			<?php } ?>


			<?php if ($component->type == \app\models\StoryComponent::STORY_COMPONENT_TYPE_WORKS) { ?>

				<div class="row">
					<?php foreach ($component->items as $workId) {
						$work = \app\models\Product2::findOneSerialized($workId['work']); ?>
						<div class="col-lg-3">
							<a href="<?=$work->getViewLink()?>">
								<img src="<?=$work->getMainImage()?>" class="img-responsive" />
								<p><?=Utils::l($work->name)?></p>
							</a>
						</div>
					<?php } ?>
				</div>

			<?php } ?>

		<?php } ?>

	</div>
</div>

