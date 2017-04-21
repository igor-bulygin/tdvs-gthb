<?php
use app\assets\desktop\pub\StoriesViewAsset;
use app\models\Person;
use yii\helpers\Json;

/** @var Person $person */
/** @var \app\models\Story $story */

$this->title = 'Story '.$story->getTitle().' by ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'stories';
$this->params['person_links_target'] = 'public_view';

$this->registerJs("var story = ".Json::encode($story), yii\web\View::POS_HEAD, 'story-script');

?>

Story <?=$story->getTitle()?>