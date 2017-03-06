<?php
use app\assets\desktop\pub\PublicCommonAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;

PublicCommonAsset::register($this);

/** @var Person $person */

$this->title = 'About ' . $person->personalInfoMapping->getBrandName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'faq';
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
					<?php if (count($faq) == 0) { ?>
						<div class="empty-wrapper">
							<?php if ($person->isPersonEditable()) { ?>
								<div><a class="red-link-btn" href="<?= $person->getFaqEditLink()?>">Add / edit questions</a></div>
							<?php } ?>
							<img class="sad-face" src="/imgs/sad-face.svg">
							<p class="no-video-text">You don't have any questions!</p>
						</div>
					<?php } else { ?>
				<div class="faq-wrapper">
						<?php if ($person->isPersonEditable()) { ?>
							<div><a class="red-link-btn" href="<?= $person->getFaqEditLink()?>">Add / edit questions</a></div>
						<?php } ?>
						<div id="accordion" role="tablist" aria-multiselectable="true">
							<?php foreach ($faq as $key => $item) { ?>
							<div class="panel faq-panel">
								<div class="panel-heading panel-heading-faq" role="tab" id="heading-faq-<?= $key ?>">
									<h4 class="panel-title">
										<a class="faq-title <?= ($key!=0) ? 'collapsed' : '' ?>" role="button" data-toggle="collapse" data-parent="#accordion"
										   href="#collapse-faq-<?= $key ?>" aria-expanded="<?= ($key==0) ? 'true' : 'false' ?>" aria-controls="collapse-faq-<?= $key ?>">
											<?= $item["question"] ?>
										</a>
									</h4>
								</div>
								<div id="collapse-faq-<?= $key ?>" class="panel-collapse collapse <?= ($key==0) ? 'in' : '' ?>" role="tabpanel"
									 aria-labelledby="heading-faq-<?= $key ?>">
									<div class="panel-body faq-answer">
										<?= $item["answer"] ?>
									</div>
								</div>
							</div>
							<?php }  ?>
						</div>
				</div>
					<?php } ?>
				</div>
		</div>
	</div>
</div>

