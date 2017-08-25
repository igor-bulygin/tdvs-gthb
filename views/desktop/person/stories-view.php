<?php
use app\assets\desktop\deviser\IndexStoryAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;

IndexStoryAsset::register($this);


/** @var Person $person */
/** @var \app\models\Story[] $stories */

$this->title = 'Stories by ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'stories';
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
				 <?php if (empty($stories)) { ?>

					<div class="empty-wrapper">
						<?php if ($person->isPersonEditable()) { ?>
							<div>
								<a class="red-link-btn stories-exemple-link" href="#" data-toggle="modal" data-target="#exampleModal"><span translate="SEE_EXAMPLE"></span></a>
							</div>
							<img class="newspaper-stories-icon" src="/imgs/no-stories.jpg">
							<p class="no-story-text"><span translate="STORIES_IS"></span></p>
							<p class="no-story-text-bold"><span translate="STORIES_EXPRESS"></span></p>
							<a class="btn btn-green btn-create-story" href="<?=$person->getStoryCreateLink()?>"><span translate="WRITE_STORY"></span></a>
						<?php } else { ?>
							<img class="sad-face" src="/imgs/no-stories.jpg">
							<p class="no-video-text"><?=$person->getName()?> <span translate="NO_STORIES_YET"></span></p>
						<?php } ?>
					</div>

				<?php } else { ?>

					<div class="content-store">
						<div class="store-grid">
							<div class="title-wrapper-stories title-wrapper title-wrapper-boxes">
								<span class="title"><span translate="STORIES_BY"></span> <?=$person->getName()?></span>
							</div>
							<div class="row">
								<?php if ($person->isPersonEditable()) { ?>
									<div class="col-lg-6">
										<a href="<?=$person->getStoryCreateLink()?>">
											<div class="box-loader-wrapper">
												<div class="plus-add-wrapper">
													<div class="plus-add">
														<span>+</span>
													</div>
													<div class="text"><span translate="WRITE_A_STORY"></span></div>
												</div>
											</div>
										</a>
									</div>
								<?php } ?>

								<?php foreach ($stories as $story) { ?>
									<a href="<?=$story->getViewLink()?>">
										<div class="col-lg-6">
											<div class="storie-box-wrapper">
												<div class="storie-box-text">
													<h5><?=$story->title?></h5>
													<p><?=$story->getFirstText()?></p>
													<div>
														<div class="loved-comments-wrapper">
															<div class="loved-wrapper">
																<i class="ion-ios-heart"></i>
																<span>342</span>
															</div>
															<div class="comments-wrapper">
																<i class="ion-chatbox"></i>
																<span>15</span>
															</div>
														</div>
													</div>
												</div>
												<?php if ($story->mainMediaMapping->type == \app\models\StoryMainMedia::STORY_MAIN_MEDIA_TYPE_PHOTO) { ?>
												<div class="storie-box-image">
													<img src="<?=$story->getMainPhotoUrl()?>">
												</div>
												<?php } ?>
											</div>
										</div>
									</a>
								<?php } ?>

							</div>
						</div>
					</div>

				<?php } ?>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<img src="/imgs/story-example.jpg" alt="Story example" title="Story example" width="100%">
			</div>
		</div>
	</div>
</div>