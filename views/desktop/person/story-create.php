<?php

use app\assets\desktop\deviser\IndexStoryAsset;
use app\models\Person;
use yii\helpers\Json;

IndexStoryAsset::register($this);

/** @var Person $person */
$this->title = Yii::t('app/public',
	'NEW_STORY_BY_PERSON_NAME',
	['person_name' => $person->getName()]
);
$this->params['person'] = $person;
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>
<div class="store">
	<div class="container" ng-controller="createStoryCtrl as createStoryCtrl">
		<div class="stories-wrapper">
			<h5 class="stories-title"><span translate="person.stories.NEW_STORY"></span></h5>
			<story-main-title story="createStoryCtrl.story" languages="createStoryCtrl.languages"></story-main-title>
			<story-main-media story="createStoryCtrl.story"></story-main-media>
			<div ui-sortable ng-model="createStoryCtrl.story.components">
				<move-delete-component class="story-component-wrapper" array="createStoryCtrl.story.components" position="$index" ng-repeat="component in createStoryCtrl.story.components">
					<div class="story-component-wrapper">
						<story-text-component component="component" languages="createStoryCtrl.languages" ng-if="component.type === 1"></story-text-component>
						<story-photo-component component="component" ng-if="component.type === 2"></story-photo-component>
						<story-work-component component="component" devisers="createStoryCtrl.devisers" ng-if="component.type === 3"></story-work-component>
						<story-video-component component="component" ng-if="component.type === 4"></story-video-component>
					</div>
				</move-delete-component>
			</div>
			<story-add-component story="createStoryCtrl.story"></story-add-component>
			<story-category-component story="createStoryCtrl.story" categories="createStoryCtrl.categories"></story-category-component>
			<story-tag-component story="createStoryCtrl.story" languages="createStoryCtrl.languages"></story-tag-component>
			<div class="text-center" style="display: block; width: 100%; float: left; margin:20px 0 100px;">
				<button class="btn btn-default btn-red" ng-click="createStoryCtrl.save(createStoryCtrl.story)"><span translate="person.stories.PUBLISH_STORY"></span></button>
			</div>
		</div>
	</div>
</div>