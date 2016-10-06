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
			<span tooltip-placement="top" uib-tooltip="ADD COVER PHOTO" class="button ion-camera edit-cover-photo-icon" ngf-select ng-model="editHeaderCtrl.new_header" name="header" ngf-pattern="'image/*'" ngf-accept="'image/*'"></span>
			<!--<span tooltip-placement="top" uib-tooltip="CROP PHOTO" class="button ion-crop edit-cover-crop-icon" ng-click="editHeaderCtrl.openCropModal(editHeaderCtrl.header_original, 'header_cropped')" ng-if="editHeaderCtrl.header"></span>-->
			<img class="cover" ngf-thumbnail="editHeaderCtrl.header || '/imgs/default-cover.jpg'">
			<div class="banner-deviser-content">
				<div class="grey-overlay"></div>
				<div class="container deviser-header-edit-wrapper">
					<div class="deviser-profile">
					    <div class="avatar-buttons-wrapper">
                            <div class="avatar">
                                <img class="cover" ngf-thumbnail="editHeaderCtrl.profile || '/imgs/default-avatar.jpg' ">
                                <span tooltip-placement="top" uib-tooltip="ADD PHOTO" class="button ion-camera edit-avatar-photo-icon" ngf-select ng-model="editHeaderCtrl.new_profile" name="profile" ngf-pattern="'image/*'" ngf-accept="'image/*'"></span>
                                <!--<span tooltip-placement="top" uib-tooltip="CROP PHOTO" class="button ion-crop crop-avatar-photo-icon" ng-if="editHeaderCtrl.profile_original" ng-click="editHeaderCtrl.openCropModal(editHeaderCtrl.profile_original, 'profile_cropped')"></span>-->
                            </div>

                            <!-- buttons -->
                            <div>
                                <div ng-if="!editHeaderCtrl.isProfilePublic">
                                    <button class="btn btn-default btn-green" ng-click="editHeaderCtrl.updateAll()">Save progress</button>
                                </div>
                                <div ng-if="!editHeaderCtrl.isProfilePublic">
                                    <button class="btn btn-default btn-green">Save changes</button>
                                    <button class="btn btn-default">Cancel</button>
                                </div>
                            </div>
						</div>
					</div>
					<div class="deviser-data-edit">
							<form class="grey-form" name="editHeaderCtrl.form">
								<!-- Brand name -->
								<div>
									<label for="brand_name">Brand name</label>
									<input type="text" class="form-control ng-class:{'error-input': editHeaderCtrl.has_error(editHeaderCtrl.form, editHeaderCtrl.form.brand_name)}" ng-model="editHeaderCtrl.deviser.personal_info.brand_name" placeholder="{{editHeaderCtrl.deviser.name}}" name="brand_name" required>
								</div>
								<!-- City -->
								<div>
									<label for="city">City</label>
									<input type="text" class="form-control ng-class:{'error-input': editHeaderCtrl.has_error(editHeaderCtrl.form, editHeaderCtrl.form.city)}" g-places-autocomplete ng-model="editHeaderCtrl.city" options="editHeaderCtrl.gApiOptions" placeholder="Your city" name="city" required>
								</div>
								<!-- Short biography -->
								<label for="text_short_description">Short description of your brand</label>
								<span class="small-grey">Translate your description by selecting different languages below</span>
									<!-- language selector -->
									<ol class="nya-bs-select about-edit-select header-lang" ng-model="editHeaderCtrl.description_language" ng-cloak>
										<li nya-bs-option="language in editHeaderCtrl.languages" data-value="language.code" deep-watch="true">
											<a href=""><span ng-bind="language.name"></span></a>
										</li>
									</ol>
								<div class="short-description-input-wrapper">
									<textarea cols="50" rows="10" class="form-control ng-class:{'error-input': editHeaderCtrl.has_error(editHeaderCtrl.form, editHeaderCtrl.form.text_short_description)}" ng-model="editHeaderCtrl.deviser.text_short_description[editHeaderCtrl.description_language]" placeholder="Please write a short description here." name="text_short_description" required></textarea>
								</div>
								<!-- counter for short biography -->
								<span class="text-limitation" ng-cloak>
									<span ng-bind="editHeaderCtrl.limit_text_biography - editHeaderCtrl.deviser.text_short_description[editHeaderCtrl.description_language].length"></span>
								</span>
							</form>
						</div>
				</div>
			</div>
		</div>
	</div>
