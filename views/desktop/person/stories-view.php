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

<div class="store">
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
							<div class="title-wrapper-stories title-wrapper">
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

								<?php /***** PUT HERE STATIC STORIES AS HTML EXAMPLE *****/ ?>

								<?php if (false) { ?>
								<?php foreach ($stories as $story) {
									$firstText = $story->getFirstTextComponent();
									$text = $firstText ? $firstText->getText() : null;
									$photoUrl = $story->mainMediaMapping->getPhotoUrl(); ?>
									<a href="<?=$story->getViewLink()?>">
										<div class="col-lg-6">

											<div class="title"><?= $story->getTitle() ?></div>

											<div><?=$text?></div>

											<div>
												<span class="ion-heart"></span>
												<span class="fa fa-comment"></span>
											</div>

											<?php if ($photoUrl) { ?>
												<img src="<?=$photoUrl?>" class="img-responsive" />
											<?php } ?>

										</div>
									</a>
								<?php } ?>
								<?php } ?>

							</div>
						</div>
					</div>

				<?php } ?>
			</div>
		</div>
	</div>
</div>

