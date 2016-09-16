<?php
use app\components\DeviserHeader;
use app\components\DeviserMenu;
use app\models\Person;
use app\models\Product;
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use app\assets\desktop\pub\Index2Asset;
use app\assets\desktop\deviser\EditAboutAsset;

EditAboutAsset::register($this);

/** @var Person $deviser */

$this->title = 'About ' . $deviser->getBrandName() . ' - Todevise';
$this->params['deviser_menu_active_option'] = 'about';
$this->params['deviser'] = $deviser;

?>

	<?= DeviserHeader::widget() ?>

		<div class="store">
			<div class="container">
				<div class="row">
					<div class="col-md-2">
						<?= DeviserMenu::widget() ?>
					</div>
					<div class="col-md-10 about-bg" ng-controller="editAboutCtrl as editAboutCtrl">
						<div class="col-md-5 pad-about">
							<div class="about-wrapper">
								<div class="about-container">
									<!--<div class="title">Abo<br>ut</div>-->
									<div class="name-location-wrapper">
										<div class="name" ng-bind="editAboutCtrl.deviser.name">
										</div>
										<div class="location">
											<?= $deviser->getLocationLabel() ?>
										</div>
									</div>
									<div class="subtitle">
										<ol class="nya-bs-select" ng-model="editAboutCtrl.deviser.categories" multiple ng-cloak ng-if="editAboutCtrl.categories">
											<li nya-bs-option="category in editAboutCtrl.categories" data-value="category.id" deep-watch="true">
												<a href="">{{category.name}} <span class="glyphicon glyphicon-ok check-mark"></span></a>
											</li>
										</ol>
									</div>
									<?php if ($deviser->hasResumeFile()) { ?>
										<div class="resume-header"><a href="<?= $deviser->getUrlResumeFile() ?>">See resume</a></div>
										<?php } ?>
											<ol class="nya-bs-select" ng-model="editAboutCtrl.biography_language" ng-cloak>
												<li nya-bs-option="language in editAboutCtrl.languages" data-value="language.code" deep-watch="true">
													<a href=""><span ng-bind="language.name"></span></a>
												</li>
											</ol>
											<div text-angular ta-text-editor-class="header" ng-model="editAboutCtrl.deviser.text_biography[editAboutCtrl.biography_language]" ng-cloak ta-toolbar="[]"></div>
											<span class="glyphicon glyphicon-pencil" style="color:white;"></span>
											<div class="text-center">
												<button class="btn btn-default" ng-click="editAboutCtrl.update()">Update</button>
											</div>
								</div>
							</div>
						</div>
						<div class="col-md-7 pad-about about-grid">
							<div ng-if="editAboutCtrl.isDropAvailable">
								<div class="photo-loader" ng-model="editAboutCtrl.image" ngf-drop ngf-select ngf-accept="'image/*'" ngf-drop-available="editAboutCtrl.isDropAvailable">
									<div class="plus-add">+</div>
									<span>Add photo</span>
								</div>
							</div>
							<div ngf-no-file-drop>
								<input type="file" name="file" ng-model="editAboutCtrl.image" ngf-select ngf-accept="'image/*'" ngf-drop-available="editAboutCtrl.isDropAvailable">
								<button ng-click="editAboutCtrl.upload(editAboutCtrl.image)">Add</button>
							</div>
							<div class="col-xs-6 pad-about item draggable-list" dnd-list="editAboutCtrl.images" ng-repeat="image in editAboutCtrl.images" ng-if="editAboutCtrl.images.length > 0" ng-cloak>
								<div class="image-press-wrapper">
									<span class="ion-android-close x-close" ng-click="editAboutCtrl.deleteImage($index)"></span>
									<img ng-src="{{image.url}}" class="grid-image" class="draggable-img" dnd-draggable="image" dnd-effect-allowd="move" dnd-moved="editAboutCtrl.update($index)">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>