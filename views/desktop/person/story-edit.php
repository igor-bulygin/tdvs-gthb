<?php
use app\models\Person;
use yii\helpers\Json;


/** @var Person $person */

$this->params['person'] = $person;
$this->title = $person->getName() . ' - Todevise';
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>
Edit story