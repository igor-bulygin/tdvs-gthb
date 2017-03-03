<?php
use app\assets\desktop\deviser\EditAboutAsset;
use app\components\MakeProfilePublic;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;
use yii\helpers\Json;

EditAboutAsset::register($this);

/** @var Person $person */

$this->title = 'About ' . $person->personalInfoMapping->getBrandName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'about';
$this->params['person_links_target'] = 'edit_view';

$this->registerJs("var deviser = ".Json::encode($person), yii\web\View::POS_HEAD, 'deviser-var-script');

?>

	<?php if ($person->isDraft()) { ?>
		<?= MakeProfilePublic::widget() ?>
	<?php } ?>
	<?= PersonHeader::widget() ?>
	<div class="store">
		<div class="container">
			<div class="row">
				<div class="col-md-2">
					<?= PersonMenu::widget() ?>
				</div>
				<edit-about></edit-about>
			</div>
		</div>
	</div>