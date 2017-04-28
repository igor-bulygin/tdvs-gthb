<?php
use app\models\Person;
use yii\helpers\Json;
use app\assets\desktop\deviser\EditStoryAsset;

EditStoryAsset::register($this);

/** @var Person $person */

$this->params['person'] = $person;
$this->title = $person->getName() . ' - Todevise';
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');


?>
<div class="store">
	<div class="container" ng-controller="editStoryCtrl as editStoryCtrl">
		<div class="stories-wrapper">
			
		</div>
	</div>
</div>