<?php

use app\assets\desktop\deviser\GlobalAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\helpers\Utils;
use app\models\Person;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = Yii::t('app/public',
	'PERSON_NAME_FAQS',
	['person_name' => $person->getName()]
);

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
					<?php if (count($person->faqMapping) == 0) { ?>
						<div class="empty-wrapper">
							<?php if ($person->isPersonEditable()) { ?>
								<div><a class="red-link-btn" href="<?= $person->getFaqEditLink()?>"><span translate="person.faq.ADD_EDIT_QUESTIONS"></span></a></div>
							<?php } ?>
							<img class="sad-face" src="/imgs/sad-face.svg">
							<p class="no-video-text"><span translate="person.faq.NO_QUESTIONS"></span></p>
						</div>
					<?php } else { ?>
				<div class="faq-wrapper">
						<?php if ($person->isPersonEditable()) { ?>
							<div><a class="red-link-btn" href="<?= $person->getFaqEditLink()?>"><span translate="person.faq.ADD_EDIT_QUESTIONS"></span></a></div>
						<?php } ?>
						<div id="accordion" role="tablist" aria-multiselectable="true">
							<?php foreach ($person->faqMapping as $key => $item) { ?>
							<div class="panel faq-panel">
								<div class="panel-heading panel-heading-faq" role="tab" id="heading-faq-<?= $key ?>">
									<h4 class="panel-title">
										<a class="faq-title <?= ($key!=0) ? 'collapsed' : '' ?>" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-faq-<?= $key ?>" aria-expanded="<?= ($key==0) ? 'true' : 'false' ?>" aria-controls="collapse-faq-<?= $key ?>">
											<?= Utils::l($item["question"]) ?>
										</a>
									</h4>
								</div>
								<div id="collapse-faq-<?= $key ?>" class="panel-collapse collapse <?= ($key==0) ? 'in' : '' ?>" role="tabpanel"
									 aria-labelledby="heading-faq-<?= $key ?>">
									<div class="panel-body faq-answer">
										<?= Utils::l($item["answer"]) ?>
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

