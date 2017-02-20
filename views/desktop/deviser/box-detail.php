<?php
use app\assets\desktop\pub\LovedViewAsset;
use app\components\DeviserHeader;
use app\components\DeviserMenu;
use app\models\Person;

LovedViewAsset::register($this);

/** @var Person $deviser */
/** @var \app\models\Box $box */

$this->title = 'Box '.$box->name.' by ' . $deviser->personalInfoMapping->getBrandName() . ' - Todevise';
$this->params['deviser'] = $deviser;
$this->params['deviser_menu_active_option'] = 'boxes';
$this->params['deviser_links_target'] = 'public_view';

/** array $faq */

// <a class="edit-faq-btn" href="<***?= Url::to(["deviser/faq-edit", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?****>">+ ADD / EDIT QUESTIONS</a>


?>

<?= DeviserHeader::widget() ?>

<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= DeviserMenu::widget() ?>
			</div>
			<div class="col-md-10">
            </div>
		</div>
	</div>
</div>

