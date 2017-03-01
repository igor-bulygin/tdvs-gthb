<?php
use app\assets\desktop\deviser\EditAboutAsset;
use app\components\DeviserHeader;
use app\components\DeviserMakeProfilePublic;
use app\components\DeviserMenu;
use app\models\Person;
use yii\helpers\Json;

EditAboutAsset::register($this);

/** @var Person $deviser */

$this->title = 'About ' . $deviser->personalInfoMapping->getBrandName() . ' - Todevise';
$this->params['deviser_menu_active_option'] = 'about';
$this->params['deviser_links_target'] = 'edit_view';
$this->params['deviser'] = $deviser;

$this->registerJs("var deviser = ".Json::encode($deviser), yii\web\View::POS_HEAD, 'deviser-var-script');

?>

	<?php if ($deviser->isDraft()) { ?>
		<?= DeviserMakeProfilePublic::widget() ?>
	<?php } ?>
	<?= DeviserHeader::widget() ?>
	<div class="store">
		<div class="container">
			<div class="row">
				<div class="col-md-2">
					<?= DeviserMenu::widget() ?>
				</div>
				<edit-about></edit-about>
			</div>
		</div>
	</div>