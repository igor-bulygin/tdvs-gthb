<?php
use app\models\Person;
use app\assets\desktop\deviser\CreateStoryAsset;
use yii\helpers\Json;

CreateStoryAsset::register($this);

/** @var Person $person */
/** @var Product $product */
/** @var PersonVideo $video */

$this->title = $person->getName() . ' - Todevise';
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>
<div class="store">
	<div class="container" ng-controller="createStoryCtrl as createStoryCtrl">
		<p>New Story</p>
		<story-main-title story="createStoryCtrl.story" languages="createStoryCtrl.languages"></story-main-title>
		<story-main-media story="createStoryCtrl.story"></story-main-media>
	</div>
</div>