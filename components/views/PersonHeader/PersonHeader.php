<?php

use app\assets\desktop\deviser\GlobalAsset;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

// use params to share data between views :(
/** @var Person $person */
$person = $this->params['person'];
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');
?>

<div class="banner-deviser" ng-controller="personHeaderCtrl as personHeaderCtrl">
	<div class="container pad-about" ng-if="!personHeaderCtrl.editingHeader" ng-cloak>
		<img class="cover" ng-src="{{personHeaderCtrl.person.header_image}}">
		<div class="banner-deviser-content">
			<div class="grey-overlay hidden-xs hidden-sm"></div>
			<div class="container">
				<div class="deviser-profile">
					<div class="avatar-btn-profile">
						<div class="avatar">
							<div class="icon-deviser" ng-if="personHeaderCtrl.person.type.indexOf(2)>=0" ng-cloak>
								<img src="/imgs/deviser.svg">
							</div>
							<div class="icon-deviser" ng-if="personHeaderCtrl.person.type.indexOf(3)>=0" ng-cloak>
								<img src="/imgs/estrella.svg">
							</div>
							<img class="cover" ng-src="{{personHeaderCtrl.person.profile_image}}">
						</div>
						<div class="type-of-user hidden-xs hidden-sm" ng-if="personHeaderCtrl.person.type.indexOf(2)>=0" ng-cloak>
							<span translate="global.DEVISER"></span>
						</div>
						<div class="type-of-user hidden-xs hidden-sm" ng-if="personHeaderCtrl.person.type.indexOf(3)>=0" ng-cloak>
							<span translate="global.INFLUENCER"></span>
						</div>
						<?php if ($person->isPersonEditable() && $person->isPublic()) {?>
							<div class="edit-profile-btn hidden-xs hidden-sm">
								<button class="btn btn-default all-caps btn-black-on-white btn-header ng-class:{'button-error': personHeaderCtrl.required['header_info']}" ng-click="personHeaderCtrl.editHeader()"><span translate="person.header.EDIT_HEADER"></span></button>
							</div>
						<?php } ?>
						<div class="deviser-data">
							<div class="name">
								{{personHeaderCtrl.person.name}}
							</div>
							<div class="location">
								{{personHeaderCtrl.city}}
							</div>
							<div class="description hidden-xs hidden-sm">
								{{personHeaderCtrl.person.text_short_description}}
							</div>
						</div>
					</div>
				</div>
				<?php if ($person->isDeviserEditable() && $person->isPublic()) {?>
					<a class="btn btn-default all-caps btn-header btn-add-work hidden-xs hidden-sm" ng-class="personHeaderCtrl.required['store'] ? 'button-error' : 'btn-red'" href="<?= $person->getCreateWorkLink()?>"><span translate="person.header.ADD_WORK"></span></a>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="container pad-about edit-header-container" ng-if="personHeaderCtrl.editingHeader" ng-cloak>
		<span tooltip-placement="top" uib-tooltip="{{'person.header.ADD_COVER_PHOTO' | translate }}" class="button ion-camera edit-cover-icon photo" ngf-select ng-model="personHeaderCtrl.new_header" name="header" ngf-pattern="'image/*'" ngf-accept="'image/*'"></span>
		<span tooltip-placement="top" uib-tooltip="{{'person.header.CROP_PHOTO' | translate}}" class="button ion-crop edit-cover-icon crop" ng-click="personHeaderCtrl.openCropModal(personHeaderCtrl.header_original, 'header_cropped')" ng-if="personHeaderCtrl.header" ng-cloak></span>
		<span class="req-1" ng-if="personHeaderCtrl.required['header'] && !personHeaderCtrl.new_header" ng-cloak><span translate="global.REQUIRED"></span></span>
		<img class="cover" ngf-thumbnail="personHeaderCtrl.header || '/imgs/default-cover.jpg'" style="height: 388.5px;">
		<div class="banner-deviser-content banner-deviser-edit-header-content">
			<div class="grey-overlay hidden-xs hidden-sm"></div>
			<div class="container deviser-header-edit-wrapper">
				<div class="deviser-profile">
					<div class="avatar-buttons-wrapper">
						<div class="avatar">
							<img class="cover" ngf-thumbnail="personHeaderCtrl.profile || '/imgs/default-avatar.png'">
							<span tooltip-placement="top" uib-tooltip="{{'person.header.ADD_PHOTO' | translate }}" class="button ion-camera edit-avatar-photo-icon ng-class:{'two':personHeaderCtrl.profile_original}" ngf-select ng-model="personHeaderCtrl.new_profile" name="profile" ngf-pattern="'image/*'" ngf-accept="'image/*'"></span>
							<span tooltip-placement="top" uib-tooltip="{{'person.header.CROP_PHOTO' | translate}}" class="button ion-crop crop-avatar-photo-icon" ng-if="personHeaderCtrl.profile_original" ng-click="personHeaderCtrl.openCropModal(personHeaderCtrl.profile_original, 'profile_cropped')" ng-cloak></span>
							<div ng-if="personHeaderCtrl.required['profile'] && !personHeaderCtrl.new_profile" ng-cloak><p class="req-2"><span translate="global.REQUIRED"></span></p></div>
						</div>

						<!--BUTTONS-->
						<div class="header-edit-btns">
							<button class="btn btn-default btn-red btn-header" ng-click="personHeaderCtrl.saveHeader()"><span translate="person.header.SAVE_EXIT"></span></button>
							<button class="btn btn-default btn-black-on-white btn-header" ng-click="personHeaderCtrl.cancelEdit()"><span translate="global.CANCEL"></span></button>
						</div>
					</div>
				</div>
				<div class="deviser-data-edit">
					<form class="grey-form" name="personHeaderCtrl.form">
						<!-- names -->
						<div ng-if="personHeaderCtrl.isDeviser(personHeaderCtrl.person)">
							<label for="brand_name"><span translate="global.user.BRAND_NAME"></span></label>
							<input type="text" class="form-control ng-class:{'error-input': personHeaderCtrl.has_error(personHeaderCtrl.form, personHeaderCtrl.form.brand_name)}" ng-model="personHeaderCtrl.person.personal_info.brand_name" placeholder="{{personHeaderCtrl.person.name}}" name="brand_name" required>
						</div>
						<div ng-if="personHeaderCtrl.isInfluencer(personHeaderCtrl.person) || personHeaderCtrl.isClient(personHeaderCtrl.person)">
							<div class="row">
								<div class="col-md-6">
								<label for="name"><span translate="global.user.FIRST_NAME"></span></label>
								<input type="text" class="form-control ng-class:{'error-input': personHeaderCtrl.has_error(personHeaderCtrl.form, personHeaderCtrl.form.name)}" ng-model="personHeaderCtrl.person.personal_info.name" placeholder="{{personHeaderCtrl.person.name}}" name="name" required>
								</div>
								<div class="col-md-6">
									<label for="last_name"><span translate="global.user.LAST_NAME"></span></label>
									<input type="text" class="form-control ng-class:{'error-input': personHeaderCtrl.has_error(personHeaderCtrl.form, personHeaderCtrl.form.last_name)}" ng-model="personHeaderCtrl.person.personal_info.last_name" placeholder="{{personHeaderCtrl.person.last_name}}" name="last_name" required>
								</div>
							</div>
						</div>
						<!-- city -->
						<div>
							<label for="city"><span translate="global.user.CITY"></span></label>
							<input type="text" class="form-control ng-class:{'error-input': personHeaderCtrl.has_error(personHeaderCtrl.form, personHeaderCtrl.form.city)}" ng-model="personHeaderCtrl.city" ng-model-options='{ debounce: 500 }' ng-change="personHeaderCtrl.searchPlace(personHeaderCtrl.city)" translate-attr="{placeholder: 'person.header.YOUR_CITY'}" name="city" required autocomplete="off">
						</div>
						<div ng-if="personHeaderCtrl.showCities" ng-cloak>
							<ul class="city-selection">
								<li ng-repeat="city in personHeaderCtrl.cities"><span ng-click="personHeaderCtrl.selectCity(city)" style="cursor:pointer;">{{city.city}} - {{city.country_name}}</span>
								</li>
								<li>
									<img class="powered-google" src="/imgs/powered_by_google_on_white_hdpi.png">
								</li>
							</ul>
						</div>
						<!-- short biography -->
						<label for="text_short_description">
							<span ng-if="personHeaderCtrl.isDeviser" ng-cloak translate="person.header.SHORT_BRAND_DESCRIPTION"></span>
							<span ng-if="!personHeaderCtrl.isDeviser" ng-cloak translate="person.header.SHORT_YOUR_DESCRIPTION"></span>
						</label>
						<span class="small-grey"><span translate="person.header.TRANSLATE_DESCRIPTION"></span></span>
							<!-- language selector -->
							<ol class="nya-bs-select about-edit-select header-lang" ng-model="personHeaderCtrl.description_language" ng-cloak>
								<li nya-bs-option="language in personHeaderCtrl.languages" data-value="language.code" deep-watch="true">
									<a href=""><span ng-bind="language.name"></span></a>
								</li>
							</ol>
						<div class="short-description-input-wrapper">
							<textarea name="text_short_description" cols="50" rows="10" class="form-control ng-class:{'error-input': personHeaderCtrl.has_error(personHeaderCtrl.form, personHeaderCtrl.form.text_short_description)}" ng-model="personHeaderCtrl.person.text_short_description[personHeaderCtrl.description_language]" translate-attr="{placeholder: 'person.header.WRITE_DESCRIPTION_HERE'}" required></textarea>
						</div>
						<!-- counter for short biography -->
						<span class="text-limitation" ng-cloak>
							<span ng-bind="personHeaderCtrl.limit_text_biography - personHeaderCtrl.person.text_short_description[personHeaderCtrl.description_language].length"></span>
						</span>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
