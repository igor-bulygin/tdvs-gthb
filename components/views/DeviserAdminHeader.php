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
			<span class="button ion-camera edit-cover-photo-icon" ngf-select ng-model="editHeaderCtrl.new_header" name="header" ngf-pattern="'image/*'" ngf-accept="'image/*'"></span>
			<!--<span class="button ion-crop" style="font-size:50px;" ng-click="editHeaderCtrl.openCropModal(editHeaderCtrl.header, 'header')"></span>-->
			<img class="cover" ngf-thumbnail="editHeaderCtrl.header || '/imgs/default-cover.jpg'">
			<div class="banner-deviser-content">
				<div class="grey-overlay"></div>
				<div class="container">
					<div class="deviser-profile">
						<div class="avatar">
							<img class="cover" ngf-thumbnail="editHeaderCtrl.profile || '/imgs/default-avatar.jpg' ">
							<span class="button ion-camera edit-avatar-photo-icon" ngf-select ng-model="editHeaderCtrl.new_profile" name="profile" ngf-pattern="'image/*'" ngf-accept="'image/*'"></span>
							<span class="button ion-crop crop-avatar-photo-icon" ng-click="editHeaderCtrl.openCropModal(editHeaderCtrl.profile, 'profile')"></span>
						</div>
						<div class="deviser-data">
							<div class="name" text-angular ta-text-editor-class="header" ng-model="editHeaderCtrl.brand_name" ng-cloak ta-toolbar="[]" style="max-height:50px;" ng-blur="editHeaderCtrl.update('brand_name', editHeaderCtrl.brand_name)"></div>
							<div class="location" ng-model="editHeaderCtrl.deviser.personal_info.city" text-angular ta-text-editor-class="header" ng-cloak ta-toolbar="[]" style="max-height:50px;" ng-blur="editHeaderCtrl.update('city', editHeaderCtrl.deviser.personal_info.city)"></div>
							<ol class="nya-bs-select about-edit-select header-lang" ng-model="editHeaderCtrl.description_language" ng-cloak>
								<li nya-bs-option="language in editHeaderCtrl.languages" data-value="language.code" deep-watch="true">
									<a href=""><span ng-bind="language.name"></span></a>
								</li>
							</ol>
							<span class="glyphicon glyphicon-pencil pencil-edit"></span>
							<div style="width:100%;float:left;position:elative;">
                                <div class="description" text-angular ta-text-editor-class="header" ng-model="editHeaderCtrl.deviser.text_short_description[editHeaderCtrl.description_language]" ng-cloak ta-toolbar="[]" style="max-height:50px;" ng-blur="editHeaderCtrl.update('text_short_description', editHeaderCtrl.deviser.text_short_description)"></div>
                                <span class="glyphicon glyphicon-pencil pencil-edit absolute-pencil"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>