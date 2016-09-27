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
			<span tooltip-placement="top" uib-tooltip="ADD CROP" class="button ion-crop edit-cover-crop-icon" ng-click="editHeaderCtrl.openCropModal(editHeaderCtrl.header_original, 'header')" ng-if="editHeaderCtrl.header"></span>
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
							<div contenteditable ng-model="editHeaderCtrl.deviser.personal_info.brand_name" class="name" ng-blur="editHeaderCtrl.update('personal_info', editHeaderCtrl.deviser.personal_info)" placeholder="editHeaderCtrl.deviser.name"></div>
							<div class="location" contenteditable ng-model="editHeaderCtrl.deviser.personal_info.city" ng-blur="editHeaderCtrl.update('personal_info', editHeaderCtrl.deviser.personal_info)"></div>
							<ol class="nya-bs-select about-edit-select header-lang" ng-model="editHeaderCtrl.description_language" ng-cloak>
								<li nya-bs-option="language in editHeaderCtrl.languages" data-value="language.code" deep-watch="true">
									<a href=""><span ng-bind="language.name"></span></a>
								</li>
							</ol>
							<span class="glyphicon glyphicon-pencil pencil-edit"></span>
							<div style="width:100%;float:left;position:elative;">
								<div class="description" contenteditable ng-model="editHeaderCtrl.deviser.text_short_description[editHeaderCtrl.description_language]" ng-blur="editHeaderCtrl.update('text_short_description', editHeaderCtrl.deviser.text_short_description)" ng-cloak></div><span ng-cloak>{{editHeaderCtrl.deviser.text_short_description[editHeaderCtrl.description_language].length}}/{{editHeaderCtrl.limit_text_biography}}</span>
								<span class="glyphicon glyphicon-pencil pencil-edit absolute-pencil"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>