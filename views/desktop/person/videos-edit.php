<?php

use app\assets\desktop\deviser\GlobalAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = Yii::t('app/public',
	'Edit videos by {person_name}',
	['person_name' => $person->getName()]
);
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'videos';
$this->params['person_links_target'] = 'edit_view';

?>

<?= PersonHeader::widget() ?>

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