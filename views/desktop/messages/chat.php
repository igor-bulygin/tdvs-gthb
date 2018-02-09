<?php

use app\assets\desktop\settings\GlobalAsset;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = Yii::t('app/public', 'CHAT_WITH_TITLE', ['person_name' => $personToChat->getName()]);

$this->params['person'] = $person;
$this->registerJs('var person = ' .Json::encode($person), yii\web\View::POS_HEAD);
$this->registerJs('var person_to_chat = ' .Json::encode($personToChat), yii\web\View::POS_HEAD);

?>

<div class="row">
	<div class="col-lg-4">
		List of chats
	</div>
	<div class="col-lg-8">
		Current chat
	</div>
</div>