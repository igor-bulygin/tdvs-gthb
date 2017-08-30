<?php

use app\assets\desktop\deviser\GlobalAsset;
use app\components\PersonHeader;
use app\components\PersonMenu;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */
$this->title = Yii::t('app/public',
	'EDIT_ABOUT_PERSON_NAME',
	['person_name' => $person->getName()]
);

$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'about';
$this->params['person_links_target'] = 'edit_view';

$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

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