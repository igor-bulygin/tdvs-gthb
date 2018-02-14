<?php

use app\assets\desktop\settings\GlobalAsset;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = Yii::t('app/public', 'CHAT_TITLE');

$this->params['person'] = $person;
$this->registerJs('var person = ' .Json::encode($person), yii\web\View::POS_HEAD);

?>

<div class="row">
	<div class="col-lg-4">
		List of chats
	</div>
	<div class="col-lg-8">
		Please select a chat on left
	</div>
</div>