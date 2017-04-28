<?php
use app\models\Person;
use yii\helpers\Json;
use app\assets\desktop\deviser\EditStoryAsset;

EditStoryAsset::register($this);

/** @var Person $person */

$this->params['person'] = $person;
$this->title = $person->getName() . ' - Todevise';
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');
$this->registerJs("var story = ".Json::encode($story), yii\web\View::POS_HEAD, 'story-var-script');


?>
<div class="store">
	<div class="container" ng-controller="editStoryCtrl as editStoryCtrl">
		<div class="stories-wrapper">
			<h5 class="stories-title">Edit Story</h5>
			<story-main-title story="editStoryCtrl.story" languages="editStoryCtrl.languages"></story-main-title>
			<story-main-media story="editStoryCtrl.story"></story-main-media>
			<move-delete-component array="editStoryCtrl.story.components" position="$index" ng-repeat="component in editStoryCtrl.story.components">
				<div style="display:block; width: 100%; float:left; border-width: 1px; border-style: dashed; border-color: grey;">
					<story-text-component component="component" languages="editStoryCtrl.languages" ng-if="component.type === 1"></story-text-component>
					<story-photo-component component="component" ng-if="component.type === 2"></story-photo-component>
					<story-work-component component="component" devisers="editStoryCtrl.devisers" ng-if="component.type === 3"></story-work-component>
					<story-video-component component="component" ng-if="component.type === 4"></story-video-component>
				</div>
			</move-delete-component>
			<story-add-component story="editStoryCtrl.story"></story-add-component>
			<story-category-component story="editStoryCtrl.story" categories="editStoryCtrl.categories"></story-category-component>
			<story-tag-component story="editStoryCtrl.story" languages="editStoryCtrl.languages"></story-tag-component>
			<div class="text-center" style="display: block; width: 100%; float: left; margin:50px 0 100px;">
				<button class="btn btn-default btn-green" ng-click="editStoryCtrl.save(editStoryCtrl.story)">Save story</button>
			</div>
		</div>
	</div>
</div>