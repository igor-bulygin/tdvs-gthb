<?php
use app\components\assets\cropAsset;
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Url;
use app\assets\desktop\deviser\EditHeaderAsset;

EditHeaderAsset::register($this);

// use params to share data between views :(
/** @var Person $deviser */
$deviser = $this->params['deviser'];

?>
	<div class="banner-deviser" ng-controller="editHeaderCtrl as editHeaderCtrl">
		<div class="container pad-about">
			<span tooltip-placement="top" uib-tooltip="ADD PHOTO" class="button ion-camera edit-cover-photo-icon" ngf-select ng-model="editHeaderCtrl.new_header" name="header" ngf-pattern="'image/*'" ngf-accept="'image/*'"></span>
			<span tooltip-placement="top" uib-tooltip="ADD CROP" class="button ion-crop edit-cover-crop-icon" ng-click="editHeaderCtrl.openCropModal(editHeaderCtrl.header_original, 'header_cropped')" ng-if="editHeaderCtrl.header"></span>
			<img class="cover" ngf-thumbnail="editHeaderCtrl.header || '/imgs/default-cover.jpg'">
			<div class="banner-deviser-content">
				<div class="grey-overlay"></div>
				<div class="container">
					<div class="deviser-profile">
						<div class="avatar">
							<img class="cover" ngf-thumbnail="editHeaderCtrl.profile || '/imgs/default-avatar.jpg' ">
							<span tooltip-placement="top" uib-tooltip="ADD PHOTO" class="button ion-camera edit-avatar-photo-icon" ngf-select ng-model="editHeaderCtrl.new_profile" name="profile" ngf-pattern="'image/*'" ngf-accept="'image/*'"></span>
							<span tooltip-placement="top" uib-tooltip="CROP PHOTO" class="button ion-crop crop-avatar-photo-icon" ng-if="editHeaderCtrl.profile_original" ng-click="editHeaderCtrl.openCropModal(editHeaderCtrl.profile_original, 'profile_cropped')"></span>
						</div>
						<div class="deviser-data">
							<div text-angular ta-text-editor-class="header" ng-model="editHeaderCtrl.deviser.personal_info.brand_name" class="name" ng-model-options="{debounce: 3000}" ng-change="editHeaderCtrl.update('personal_info', editHeaderCtrl.deviser.personal_info)" ng-blur="editHeaderCtrl.update('personal_info', editHeaderCtrl.deviser.personal_info)" placeholder="editHeaderCtrl.deviser.name" ta-toolbar="[]" ng-cloak style="max-height: 30px; min-height: 30px;" ta-default-wrap=""></div>
							<input type="text" class="form-control short-description-input" g-places-autocomplete ng-model="editHeaderCtrl.city" options="editHeaderCtrl.gApiOptions" ng-change="editHeaderCtrl.searchPlace()"></div>
							<ol class="nya-bs-select about-edit-select header-lang" ng-model="editHeaderCtrl.description_language" ng-cloak>
								<li nya-bs-option="language in editHeaderCtrl.languages" data-value="language.code" deep-watch="true">
									<a href=""><span ng-bind="language.name"></span></a>
								</li>
							</ol>
							<span class="glyphicon glyphicon-pencil pencil-edit"></span>
							<div style="width:100%;float:left;position:relative;">
								<input type="text" class="short-description-input" ng-model="editHeaderCtrl.deviser.text_short_description[editHeaderCtrl.description_language]" ng-model-options="{debounce: 3000}" ng-change="editHeaderCtrl.update('text_short_description', editHeaderCtrl.deviser.text_short_description)" ng-blur="editHeaderCtrl.update('text_short_description', editHeaderCtrl.deviser.text_short_description)" placeholder="Please write a short description here.">
								<span class="text-limitation" ng-cloak>{{editHeaderCtrl.deviser.text_short_description[editHeaderCtrl.description_language].length}}/{{editHeaderCtrl.limit_text_biography}}</span>
								<span class="glyphicon glyphicon-pencil pencil-edit"></span>
							</div>							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
