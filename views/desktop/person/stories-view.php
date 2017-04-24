<?php
use app\assets\desktop\deviser\StoriesViewAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;

StoriesViewAsset::register($this);


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
							<div><a class="red-link-btn" href="#">See an example</a></div>
							<img class="sad-face" src="/imgs/sad-face.svg">
							<p class="no-video-text">Express yourself, start writing your first one now.</p>
							<a class="btn btn-green btn-add-box" href="<?=$person->getStoryCreateLink()?>">WRITE STORY</a>
						<?php } else { ?>
							<img class="sad-face" src="/imgs/sad-face.svg">
							<p class="no-video-text"><?=$person->getName()?> have no stories.</p>
						<?php } ?>
					</div>

				<?php } else { ?>

					<div class="content-store">
						<div class="store-grid">
							<div class="title-wrapper-stories title-wrapper title-wrapper-boxes">
								<span class="title">Stories by <?=$person->getName()?></span>
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
													<div class="text">Write a story</div>
												</div>
											</div>
										</a>
									</div>
								<?php } ?>
								
								<div ng-if="viewStoriesCtrl.results.meta.total_count>0">
									<div ng-repeat="story in viewStoriesCtrl.results.items" ng-cloak>
										<div class="col-lg-6">
											<div class="storie-box-wrapper">
												<div class="storie-box-text">
													<h5 ng-bind="story.title"></h5>
													<p ng-bind="story.first_text"></p>
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
												<div class="storie-box-image" ng-if="story.main_media.type===1">
													<img ng-src="{{story.main_photo_url}}">
												</div>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>

				<?php } ?>
			</div>
		</div>
	</div>
</div>

