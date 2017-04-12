<?php
use app\assets\desktop\deviser\EditPressAsset;
use app\components\MakeProfilePublic;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;
use yii\helpers\Json;

EditPressAsset::register($this);

/** @var Person $person */

$this->title = 'About ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'press';
$this->params['person_links_target'] = 'edit_view';

$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

<?= PersonHeader::widget() ?>
<?php if ($person->isDraft()) { ?>
	<?= MakeProfilePublic::widget() ?>
<?php } ?>
<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<?= PersonMenu::widget() ?>
			</div>
			<edit-press></edit-press>
		</div>
	</div>
</div>