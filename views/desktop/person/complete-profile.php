<?php

/* @var app\models\Person $person */
use app\assets\desktop\deviser\CompleteProfileAsset;

CompleteProfileAsset::register($this);

$this->title = 'Complete your profile - Todevise';
$this->registerJs("var person = ".\yii\helpers\Json::encode($person), yii\web\View::POS_HEAD, 'complete-profile-var-script');

?>

<div class="create-deviser-account-wrapper" ng-controller="completeProfileCtrl as completeProfileCtrl">
	<div class="logo">
		<a href="#">
			<img src="/imgs/logo.png" data-pin-nopin="true">
		</a>
	</div>
	<div class="invitation-messages">
		<p>We need just a little bit more information about your brand</p>
	</div>
	<div class="create-deviser-account-container black-form">
		<form name="completeProfileCtrl.form">
			<div class="row">
				<label for="brand_name">Brand name</label>
				<input type="text" name="brand_name" class="form-control grey-input ng-class:{'error-input': completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.brand_name)}" ng-model="completeProfileCtrl.person.personal_info.brand_name" required>
				<form-errors field="completeProfileCtrl.form.brand_name" condition="completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.brand_name)"></form-errors>
			</div>
			<div class="row">
				<label for="city">City</label>
				<input type="text" name="city" class="form-control grey-input ng-class:{'error-input': completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.city)}" ng-model="completeProfileCtrl.city" ng-model-options="{debounce: 500}" ng-change="completeProfileCtrl.searchPlace(completeProfileCtrl.city)" required>
				<form-errors field="completeProfileCtrl.form.city" condition="completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.city)"></form-errors>
				<div ng-if="completeProfileCtrl.showCities" ng-cloak>
					<ul class="city-selection">
						<li ng-repeat="city in completeProfileCtrl.cities"><span ng-click="completeProfileCtrl.selectCity(city)" style="cursor:pointer;">{{city.city}} - {{city.country_name}}</span></li>
						<li><img src="/imgs/powered_by_google_on_white_hdpi.png" class="powered-google" title="Powered by Google"></li>
					</ul>
				</div>
			</div>
			<div class="row">
				<label for="categories">Field</label>
				<div>
					<ol class="work-field nya-bs-select" ng-model="completeProfileCtrl.person.categories" selected-text-format="count>4" ng-cloak ng-if="completeProfileCtrl.categories" name="categories" required multiple>
						<li nya-bs-option="category in completeProfileCtrl.categories" data-value="category.id" multiple deep-watch="true">
							<a href="#"><span ng-bind="category.name"></span> <span class="check-mark glyphicon glyphicon-ok"></span></a>
						</li>
					</ol>
				</div>
				<form-errors field="completeProfileCtrl.form.categories" condition="completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.categories)"></form-errors>
			</div>
			<hr>
			<div class="row">
				<label for="text_short_description">Short description</label>
				<textarea name="text_short_description" cols="50" rows="10" class="form-control ng-class:{'error-input': completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.text_short_description)}" ng-model="completeProfileCtrl.person.text_short_description[completeProfileCtrl.description_language]" required></textarea>
				<form-errors field="completeProfileCtrl.form.text_short_description" condition="completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.text_short_description)"></form-errors>
				<! -- language selector -->
				<ol class="nya-bs-select" ng-model="completeProfileCtrl.description_language" ng-cloak ng-if="completeProfileCtrl.languages">
					<li nya-bs-option="language in completeProfileCtrl.languages" data-value="language.code" deep-watch="true">
						<a href="#"><span ng-bind="language.name"></span></a>
					</li>
				</ol>
				<! -- Characters left -->
				<div>
					<span ng-bind="completeProfileCtrl.limit_text_short_description - completeProfileCtrl.person.text_short_description[completeProfileCtrl.description_language].length"></span>
					<span>Characters left</span>
				</div>
				<!-- tooltip -->
				<i class="fa fa-info-circle" aria-hidden="true" uib-tooltip="Describe your brand in 140 characters"></i>
			</div>
			<div class="row">
				<label for="text_biography">About</label>
				<div name="text_biography" text-angular ta-toolbar="[]" ta-paste="completeProfileCtrl.stripHTMLTags($html)" ng-model="completeProfileCtrl.person.text_biography[completeProfileCtrl.biography_language]" ng-cloak required class="ng-class:{'error-input': completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.text_biography)}"></div>
				<form-errors field="completeProfileCtrl.form.text_biography" condition="completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.text_biography)"></form-errors>
				<! -- language selector -->
				<ol class="nya-bs-select" ng-model="completeProfileCtrl.biography_language" ng-cloak ng-if="completeProfileCtrl.languages">
					<li nya-bs-option="language in completeProfileCtrl.languages" data-value="language.code" deep-watch="true">
						<a href="#"><span ng-bind="language.name"></span></a>
					</li>
				</ol>
				<!-- tooltip -->
				<i class="fa fa-info-circle" aria-hidden="true" uib-tooltip="Tell your customers more about your brand and its philosophy."></i>
			</div>
			<hr>
			<div class="row">
				<div class="ng-class:{'error-input': completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.profile)}" ngf-select ngf-accept="'image/*'" ngf-pattern="'image/*'" ng-model="completeProfileCtrl.profile" name="profile" required>
					<h4><i class="fa fa-camera"></i> Upload profile photo</h4>
					<p ng-if="!completeProfileCtrl.profile_cropped">The profile photo cannot be left blank. </p>
				</div>
				<div class="crop-modal" ng-if="completeProfileCtrl.profile" ng-cloak>
					<ui-cropper image="completeProfileCtrl.profile_crop" area-type="{{completeProfileCtrl.crop_options.profile.area_type}}" chargement="'Loading'" aspect-ratio="completeProfileCtrl.crop_options.profile.aspect_ratio" init-max-area="true" result-image="completeProfileCtrl.profile_cropped" result-image-format="'image/jpeg'" area-min-size="20" result-image-quality="0.5"></ui-cropper>
				</div>
			</div>
			<div class="row">
				<div class="ng-class:{'error-input': completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.header)}" ngf-select ngf-accept="'image/*'" ngf-pattern="'image/*'" ng-model="completeProfileCtrl.header" name="header" required>
					<h4><i class="fa fa-camera"></i> Upload cover photo</h4>
				</div>
				<div class="crop-modal" ng-if="completeProfileCtrl.header" ng-cloak>
					<ui-cropper image="completeProfileCtrl.header_crop" area-type="{{completeProfileCtrl.crop_options.header.area_type}}" chargement="'Loading'" aspect-ratio="completeProfileCtrl.crop_options.header.aspect_ratio" init-max-area="true" result-image="completeProfileCtrl.header_cropped" result-image-size="completeProfileCtrl.crop_options.header.size" result-image-format="'image/jpeg'" area-min-size="20" result-image-quality="0.5"></ui-cropper>
				</div>
			</div>
			<div class="row">
				<button class="btn-red send-btn" ng-click="completeProfileCtrl.save(completeProfileCtrl.form)">
				<i class="ion-android-navigate"></i>
			</button>
			</div>
		</form>
	</div>
</div>