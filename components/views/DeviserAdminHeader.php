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
			<span class="button glyphicon glyphicon-camera" style="font-size:50px;" ngf-select ng-model="editHeaderCtrl.header" name="header" ngf-pattern="'image/*'" ngf-accept="'image/*'" ng-change="editHeaderCtrl.openCropModal(editHeaderCtrl.header, editHeaderCtrl.croppedHeader)"></span>
			<span class="button glyphicon glyphicon-scissors" style="font-size:50px;" ng-click="editHeaderCtrl.openCropModal(editHeaderCtrl.header, editHeaderCtrl.croppedHeader)"></span>
			<img class="cover" ngf-thumbnail="editHeaderCtrl.croppedHeader || editHeaderCtrl.header" style="width: 1280px; height: 450px;">
			<div class="banner-deviser-content">
				<div class="grey-overlay"></div>
				<div class="container">
					<div class="deviser-profile">
						<div class="avatar">
							<img class="cover" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getAvatarImage())->resize(340, 340) ?>">
						</div>
						<div class="deviser-data">
							<div class="name" text-angular ta-text-editor-class="header" ng-model="editHeaderCtrl.deviser.name" ng-cloak ta-toolbar="[]" style="max-height:50px;"></div>
							<div class="location" ng-model="editHeaderCtrl.deviser.personal_info.city" text-angular ta-text-editor-class="header" ng-cloak ta-toolbar="[]" style="max-height:50px;"></div>
							<ol class="nya-bs-select" ng-model="editHeaderCtrl.description_language" ng-cloak>
								<li nya-bs-option="language in editHeaderCtrl.languages" data-value="language.code" deep-watch="true">
									<a href=""><span ng-bind="language.name"></span></a>
								</li>
							</ol>
							<div class="description" text-angular ta-text-editor-class="header" ng-model="editHeaderCtrl.deviser.text_short_description[editHeaderCtrl.description_language]" ng-cloak ta-toolbar="[]" style="max-height:50px;"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>