<?php

/* @var app\models\Person $person */

use app\assets\desktop\deviser\GlobalAsset;

GlobalAsset::register($this);

$this->title = Yii::t('app/public',
	'COMPLETE_PROFILE_PERSON_NAME',
	['person_name' => $person->getName()]
);

$this->registerJs("var person = ".\yii\helpers\Json::encode($person), yii\web\View::POS_HEAD, 'complete-profile-var-script');

?>

<div class="create-deviser-account-wrapper" ng-controller="completeProfileCtrl as completeProfileCtrl">
	<div class="logo">
		<a href="<?=\yii\helpers\Url::to('/')?>">
			<img src="/imgs/logo.png" data-pin-nopin="true">
		</a>
	</div>
	<div class="invitation-messages">
		<p><span translate="person.complete_profile.NEED_INFO" ng-if="!completeProfileCtrl.sendingForm"></span></p>
	</div>
	<div class="create-deviser-account-container black-form" ng-if="!completeProfileCtrl.sendingForm">
		<form name="completeProfileCtrl.form">
		<?php if($person->isInfluencer() || $person->isDeviser()) {?>
			<div class="row">
				<label for="first_name"><span translate="global.user.FIRST_NAME"></span></label>
				<input type="text" name="first_name" class="form-control grey-input ng-class:{'error-input': completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.name)}" ng-model="completeProfileCtrl.person.personal_info.name" required>
				<form-errors field="completeProfileCtrl.form.first_name" condition="completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.first_name)"></form-errors>
			</div>
			<div class="row">
				<label for="last_name"><span translate="global.user.LAST_NAME"></span></label>
				<input type="text" name="last_name" class="form-control grey-input ng-class:{'error-input': completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.last_name)}" ng-model="completeProfileCtrl.person.personal_info.last_name" required>
				<form-errors field="completeProfileCtrl.form.last_name" condition="completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.last_name)"></form-errors>
			</div>
			<?php } ?>
			<?php if($person->isDeviser()) {?>
				<div class="row">
					<label for="brand_name"><span translate="global.user.BRAND_NAME"></span></label>
					<input type="text" name="brand_name" class="form-control grey-input ng-class:{'error-input': completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.brand_name)}" ng-model="completeProfileCtrl.person.personal_info.brand_name" required>
					<form-errors field="completeProfileCtrl.form.brand_name" condition="completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.brand_name)"></form-errors>
				</div>
			<?php } ?>
			<?php if($person->isDeviser() || $person->isInfluencer()) {?>
				<div class="row">
					<label for="city"><span translate="global.user.CITY"></span></label>
					<input type="text" name="city" class="form-control grey-input ng-class:{'error-input': completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.city)}" ng-model="completeProfileCtrl.city" ng-model-options="{debounce: 500}" ng-change="completeProfileCtrl.searchPlace(completeProfileCtrl.city)" required autocomplete="off">
					<form-errors field="completeProfileCtrl.form.city" condition="completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.city)"></form-errors>
					<div class="relativize-me" ng-if="completeProfileCtrl.showCities" ng-cloak>
						<ul class="city-selection-center">
							<li ng-repeat="city in completeProfileCtrl.cities"><span ng-click="completeProfileCtrl.selectCity(city)" style="cursor:pointer;">{{city.city}} - {{city.country_name}}</span></li>
							<li><img src="/imgs/powered_by_google_on_white_hdpi.png" class="powered-google" title="Powered by Google"></li>
						</ul>
					</div>
				</div>
			<?php } ?>
			<?php if($person->isDeviser()) {?>
				<div class="row">
					<label for="city"><span translate="global.user.PHONE"></span></label>
				</div>
				<div class="row">
					<div class="col-xs-3 no-pad-left">
						<input type="tel" name="phone_prefix" class="form-control grey-input" ng-model="completeProfileCtrl.person.personal_info.phone_number_prefix" ng-class="{'error-input': completeProfileCtrl.validPrefix }" ng-change="completeProfileCtrl.setPrefix()" required>
					</div>
					<div class="col-xs-9 no-pad-r">
						<input type="tel" name="phone" class="form-control grey-input" ng-model="completeProfileCtrl.person.personal_info.phone_number" ng-class="{'error-input': completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.phone_number)}" required>
						<form-errors field="completeProfileCtrl.form.phone" condition="completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.phone)"></form-errors>
					</div>
				</div>

				<div class="row">
					<label for="categories"><span translate="global.FIELD"></span></label>
					<div class="ng-class:{'error-input': completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.categories)}">
						<ol class="black-form-select work-field nya-bs-select" ng-model="completeProfileCtrl.person.categories" selected-text-format="count>4" ng-cloak ng-if="completeProfileCtrl.categories" name="categories" required multiple>
							<li nya-bs-option="category in completeProfileCtrl.categories" data-value="category.id" multiple deep-watch="true">
								<a href="#"><span ng-bind="category.name"></span> <span class="check-mark glyphicon glyphicon-ok"></span></a>
							</li>
						</ol>
					</div>
					<form-errors field="completeProfileCtrl.form.categories" condition="completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.categories)"></form-errors>
				</div>
			<?php } ?>
			<div class="row">
				<?php if($person->isDeviser()) {?>
				<label class="mt-30" for="text_short_description"><span translate="person.complete_profile.SHORT_DESCRIPTION"></span></label>
				<?php } ?>
				<?php if($person->isInfluencer()) {?>
				<label for="text_short_description"><span translate="person.complete_profile.SHORT_BIO"></span></label>
				<?php } ?>
				<div class="black-area-wrappers">
				<textarea name="text_short_description" cols="50" rows="10" class="grey-textarea form-control grey-input ng-class:{'error-input': completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.text_short_description)}" ng-model="completeProfileCtrl.person.text_short_description[completeProfileCtrl.description_language]" required></textarea>
				<span class="error-text" ng-if="(completeProfileCtrl.limit_text_short_description - completeProfileCtrl.person.text_short_description[completeProfileCtrl.description_language].length)<1" ng-cloak translate="person.complete_profile.MAX_CHARACTERS_REACHED"></span>
				<form-errors field="completeProfileCtrl.form.text_short_description" condition="completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.text_short_description)"></form-errors>
				<! -- language selector -->
				<ol class="nya-bs-select area-lang-select btn-blacky" ng-model="completeProfileCtrl.description_language" ng-cloak ng-if="completeProfileCtrl.languages">
					<li nya-bs-option="language in completeProfileCtrl.languages" data-value="language.code" deep-watch="true">
						<a href="#"><span ng-bind="language.name"></span></a>
					</li>
				</ol>
				<! -- Characters left -->
				<div class="black-char-left">
					<span class="number" ng-bind="completeProfileCtrl.limit_text_short_description - completeProfileCtrl.person.text_short_description[completeProfileCtrl.description_language].length"></span>
					<span class="text"><span translate="person.complete_profile.CHARACTERS_LEFT"></span></span>
				</div>
				<!-- tooltip -->
				<?php if($person->isDeviser()) {?>
					<i class="info-black-areas ion-information-circled" aria-hidden="true" uib-popover="{{'person.complete_profile.DESCRIBE_BRAND' | translate}}"></i>
				<?php } ?>
				<?php if($person->isInfluencer()) {?>
					<i class="info-black-areas ion-information-circled" aria-hidden="true" uib-popover="{{'person.complete_profile.DESCRIBE_BIO' | translate}}"></i>
				<?php } ?>
				</div>
			</div>
			<?php if($person->isDeviser()) {?>
			<div class="row">
				<label for="text_biography"><span translate="person.complete_profile.ABOUT"></span></label>
				<div class="black-area-wrappers">
				<div name="text_biography" text-angular ta-toolbar="[]" ta-paste="completeProfileCtrl.stripHTMLTags($html)" ng-model="completeProfileCtrl.person.text_biography[completeProfileCtrl.biography_language]" ng-cloak required class="text-angular-black ng-class:{'error-input': completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.text_biography)}"></div>
				<form-errors field="completeProfileCtrl.form.text_biography" condition="completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.text_biography)"></form-errors>
				<! -- language selector -->
				<ol class="nya-bs-select btn-blacky" ng-model="completeProfileCtrl.biography_language" ng-cloak ng-if="completeProfileCtrl.languages">
					<li nya-bs-option="language in completeProfileCtrl.languages" data-value="language.code" deep-watch="true">
						<a href="#"><span ng-bind="language.name"></span></a>
					</li>
				</ol>
				<!-- tooltip -->
				<i class="info-black-areas ion-information-circled" aria-hidden="true" uib-popover="{{'person.complete_profile.DESCRIBE_ABOUT' | translate}}"></i>
				</div>
			</div>
			<?php } ?>
			<div class="row upload-photo-section" ngf-select ngf-accept="'image/*'" ngf-pattern="'image/*'" ng-model="completeProfileCtrl.profile" name="profile_photo" required>
				<div class="upload-photo-wrapper">
					<h4>
						<i class="fa fa-camera"></i>
						<span><span translate="person.complete_profile.UPLOAD_PROFILE_PHOTO"></span></span>
					</h4>
				</div>
				<div class="outter-border ng-class:{'error-input': completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.profile_photo)}" ng-if="!completeProfileCtrl.profile" ng-cloak>
					<div class="inner-square">
						<div class="inner-circle">
							<span translate="person.complete_profile.PLS_UPLOAD_PROFILE"></span>
						</div>
					</div>
				</div>
				<form-errors field="completeProfileCtrl.form.profile_photo" condition="completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.profile_photo)">
			</div>
			<div class="row upload-photo-section">
				<div class="upload-photo-wrapper">
					<div class="crop-modal" ng-if="completeProfileCtrl.profile" ng-cloak>
						<ui-cropper image="completeProfileCtrl.profile_crop" area-type="{{completeProfileCtrl.crop_options.profile.area_type}}" chargement="'Loading'" aspect-ratio="completeProfileCtrl.crop_options.profile.aspect_ratio" init-max-area="true" result-image="completeProfileCtrl.profile_cropped" result-image-format="'image/png'" area-min-size="20" result-image-quality="1.0"></ui-cropper>
					</div>
				</div>
			</div>
			<div class="row upload-photo-section" ngf-select ngf-accept="'image/*'" ngf-pattern="'image/*'" ng-model="completeProfileCtrl.header" name="header_photo" required>
				<div class="upload-photo-wrapper">
					<h4>
						<i class="fa fa-camera"></i>
						<span translate="person.complete_profile.UPLOAD_COVER_PHOTO"></span>
					</h4>
				</div>
				<div class="outter-border ng-class:{'error-input': completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.header_photo)}" ng-if="!completeProfileCtrl.header" ng-cloak>
					<div class="inner-square">
						<div class="inner-rectangle">
							<span translate="person.complete_profile.PLS_UPLOAD_COVER"></span>
						</div>
					</div>
				</div>
				<form-errors field="completeProfileCtrl.form.header_photo" condition="completeProfileCtrl.has_error(completeProfileCtrl.form, completeProfileCtrl.form.header_photo)">
			</div>
			<div class="row upload-photo-section">
				<div class="upload-photo-wrapper">
					<div class="crop-modal" ng-if="completeProfileCtrl.header" ng-cloak>
						<ui-cropper image="completeProfileCtrl.header_crop" area-type="{{completeProfileCtrl.crop_options.header.area_type}}" chargement="'Loading'" aspect-ratio="completeProfileCtrl.crop_options.header.aspect_ratio" init-max-area="true" result-image="completeProfileCtrl.header_cropped" result-image-size="completeProfileCtrl.crop_options.header.size" result-image-format="'image/png'" area-min-size="20" result-image-quality="1.0"></ui-cropper>
					</div>
				</div>
			</div>
			<div class="row">
				<button class="btn-red send-btn" ng-click="completeProfileCtrl.save(completeProfileCtrl.form)" ng-disabled="completeProfileCtrl.sendingForm">
				<img src="/imgs/plane.svg" data-pin-nopin="true">
			</button>
			</div>
		</form>
	</div>
	<div class="mt-50 tdvs-loading" ng-if="completeProfileCtrl.sendingForm">
			<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
		<span class="sr-only" translate="global.LOADING"></span>
	</div>
</div>
