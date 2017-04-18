<?php
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;


/** @var Person $person */
/** @var \app\models\Story[] $stories */

$this->title = 'Stories by ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'stories';
$this->params['person_links_target'] = 'public_view';

?>

<?= PersonHeader::widget() ?>

<div class="store" ng-controller="viewStoriesCtrl as viewStoriesCtrl">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= PersonMenu::widget() ?>
			</div>
			<div class="col-md-10">
				<?php if (empty($stories)) { ?>

					<div class="empty-wrapper">
						<?php if ($person->isConnectedUser()) { ?>
							<img class="sad-face" src="/imgs/sad-face.svg">
							<p class="no-video-text">"Stories" is your blog inside Todevise.</p>
							<p class="no-video-text">Express yourself, start writing your first one now.</p>

							<button class="btn btn-green btn-add-box" ng-click="viewStoriesCtrl.openCreateStoryModal()">WRITE STORY</button>
						<?php } else { ?>
							<p class="no-video-text"><?=$person->getName()?> have no stories.</p>
						<?php } ?>
					</div>

				<?php } else { ?>

					<div class="content-store">
						<div class="store-grid">
							<div class="title-wrapper-stories title-wrapper">
								<span class="title">Stories by <?=$person->getName()?></span>
							</div>
							<div class="row">
								<?php if ($person->isPersonEditable()) { ?>
									<div class="col-lg-4">
										<!--<button class="btn btn-default" ng-click="viewStoriesCtrl.openCreateStoryModal()">Add boxxxx</button>-->
										<div class="box-loader-wrapper" ng-click="viewStoriesCtrl.openCreateStoryModal()">
											<div class="plus-add-wrapper">
												<div class="plus-add">
													<span>+</span>
												</div>
												<div class="text">Write a story</div>
											</div>
										</div>
									</div>
								<?php } ?>
								<?php foreach ($stories as $story) { ?>
									<?= $story->getTitle() ?>
								<?php } ?>
							</div>
						</div>
					</div>

				<?php } ?>
			</div>
		</div>
	</div>
</div>

