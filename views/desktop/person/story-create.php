<?php
use app\models\Person;
use app\assets\desktop\deviser\CreateStoryAsset;
use yii\helpers\Json;

CreateStoryAsset::register($this);

/** @var Person $person */

$this->params['person'] = $person;
$this->title = $person->getName() . ' - Todevise';
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>
<div class="store">
	<div class="container" ng-controller="createStoryCtrl as createStoryCtrl">
		<p>New Story</p>
		<story-main-title story="createStoryCtrl.story" languages="createStoryCtrl.languages"></story-main-title>
		<story-main-media story="createStoryCtrl.story"></story-main-media>
		<div ng-repeat="component in createStoryCtrl.story.components">
			<story-text-component component="component" ng-if="component.type === 1"></story-text-component>
			<story-photo-component component="component" ng-if="component.type === 2"></story-photo-component>
			<story-work-component component="component" ng-if="component.type === 3"></story-work-component>
			<story-video-component component="component" ng-if="component.type === 4"></story-video-component>
		</div>
		<story-add-component story="createStoryCtrl.story"></story-add-component>
		<div class="text-center" style="display: block; width: 100%; float: left;">
			<button class="btn btn-default btn-green" ng-click="createStoryCtrl.save(createStoryCtrl.story)">Publish story</button>
		</div>
	</div>
</div>