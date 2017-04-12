<?php
use app\assets\desktop\deviser\EditVideosAsset;
use app\components\MakeProfilePublic;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;

EditVideosAsset::register($this);

/** @var Person $person */

$this->title = 'About ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'videos';
$this->params['person_links_target'] = 'edit_view';

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
			<edit-videos></edit-videos>
		</div>
	</div>
</div>