	<?php
	use app\assets\desktop\pub\StoriesViewAsset;
	use app\helpers\Utils;
	use app\models\Person;
	use yii\helpers\Json;

	\app\assets\desktop\pub\StoryDetailAsset::register($this);

/** @var Person $person */
/** @var \app\models\Story $story */

$this->title = 'Story '.$story->getTitle().' by ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'stories';
$this->params['person_links_target'] = 'public_view';

$this->registerJs("var story = ".Json::encode($story), yii\web\View::POS_HEAD, 'story-script');

?>
<div class="our-devisers-wrapper">
	<div class="container relative">
		<div class="pull-left">
		<a href="<?= $person->getMainLink()?>">
			<img class="avatar-logued-user" src="<?= $person->getAvatarImage() ?>">
			<span><?=$person->getName()?></span>
		</a>
		</div>
		<div class="pull-right">

			<?php /*
			<button class="btn btn-default" type="button">
				<i class="ion-ios-heart"></i>
				Love Story
			</button>
 			*/ ?>

			<a class="btn btn-default" href="<?=$story->getEditLink()?>">
				<i class="ion-edit"></i>
				Edit Story
			</a>

			<button class="btn btn-default" type="button">
				<i class="ion-delete"></i>
				Delete Story
			</button>
		</div>
	</div>

	<div class="container">

		<div><?= $story->getTitle() ?></div>

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

		<div>Published on May 8 2015</div>

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