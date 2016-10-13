<?php
use app\components\DeviserAdminHeader;
use app\components\DeviserHeader;
use app\components\DeviserMakeProfilePublic;
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

$this->title = 'About ' . $deviser->personalInfo->getBrandName() . ' - Todevise';
$this->params['deviser_menu_active_option'] = 'about';
$this->params['deviser_links_target'] = 'edit_view';
$this->params['deviser'] = $deviser;

?>

	<?php if ($deviser->isDraft()) { ?>
		<?= DeviserMakeProfilePublic::widget() ?>
			<?php } ?>
				<?= DeviserAdminHeader::widget() ?>
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
												<div class="title">Abo<br>ut</div>
												<form name="editAboutCtrl.form">
													<label for="fields">Choose your field(s) of work</label>
													<ol class="nya-bs-select ng-class:{'setClassCategoriesRequired': editAboutCtrl.setCategoriesRequired}" ng-model="editAboutCtrl.deviser.categories" multiple ng-cloak ng-if="editAboutCtrl.categories">
														<li nya-bs-option="category in editAboutCtrl.categories" data-value="category.id" deep-watch="true">
															<a href=""><span ng-bind="category.name"></span> <span class="glyphicon glyphicon-ok check-mark"></span></a>
														</li>
													</ol>
													<label for="text_biography">Brand Statement / Biography</label>
													<span>Translate your text by selecting different languages below.</span>
													<ol class="nya-bs-select ng-class:{'setClassBiographyRequired': editAboutCtrl.setClassBiographyRequired}" ng-model="editAboutCtrl.biography_language" ng-cloak>
														<li nya-bs-option="language in editAboutCtrl.languages" data-value="language.code" deep-watch="true">
															<a href=""><span ng-bind="language.name"></span> <span class="glyphicon glyphicon-ok check-mark"></span></a>
														</li>
													</ol>
													<div text-angular ng-model="editAboutCtrl.deviser.text_biography[editAboutCtrl.biography_language]" ng-cloak ta-toolbar="[]" placeholder="Write your brand statement / mission / biography."></div>
													<label for="resume">Resume or brand presentation</label>
													<span>OPTIONAL</span><span>Even more things to tell your customers? Upload it here.</span>
													<button class="btn btn-default btn-green" ngf-select="editAboutCtrl.uploadCV($file)" ngf-accept="'application/pdf'">UPLOAD FILE</button>
												</form>

												<!-- <div class="subtitle">
													<ol class="nya-bs-select about-edit-select" ng-model="editAboutCtrl.deviser.categories" multiple ng-cloak ng-if="editAboutCtrl.categories" ng-change="editAboutCtrl.update('categories', editAboutCtrl.deviser.categories)">
														<li nya-bs-option="category in editAboutCtrl.categories" data-value="category.id" deep-watch="true">
															<a href="">{{category.name}} <span class="glyphicon glyphicon-ok check-mark"></span></a>
														</li>
													</ol>
													<span class="glyphicon glyphicon-pencil pencil-edit"></span>
												</div> -->
												<!-- <div class="resume-header">
													<div ng-if="!editAboutCtrl.deviser.curriculum" ng-cloak>
														<button class="btn btn-default btn-green" ngf-select="editAboutCtrl.uploadCV($file)" ngf-accept="'application/pdf'">Add Resume</button>
													</div>
													<div class="see-resume-wrapper" ng-if="editAboutCtrl.deviser.curriculum" ng-cloak>
														<a ng-href="{{editAboutCtrl.curriculum}}">See resume</a> 
														<span class="ion-loop" ngf-select="editAboutCtrl.uploadCV($file)" ngf-accept="'application/pdf'"></span>
														<span class="ion-close-circled" ng-click="editAboutCtrl.deleteCV()"></span>
													</div>
												</div> -->
												<!-- <div class="editable-text-about-wrapper">
													<ol class="nya-bs-select about-edit-select lang" ng-model="editAboutCtrl.biography_language" ng-cloak>
														<li nya-bs-option="language in editAboutCtrl.languages" data-value="language.code" deep-watch="true">
															<a href=""><span ng-bind="language.name"></span> <span class="glyphicon glyphicon-ok check-mark"></span></a>
														</li>
													</ol>
													<div class="editable-text-about" text-angular ta-text-editor-class="header" ng-model="editAboutCtrl.deviser.text_biography[editAboutCtrl.biography_language]" ng-cloak ta-toolbar="[]" placeholder="Write your brand statment / mission / biography." ng-model-options="{debounce: 1000}" ng-change="editAboutCtrl.update('text_biography', editAboutCtrl.deviser.text_biography)" ng-blur="editAboutCtrl.update('text_biography', editAboutCtrl.deviser.text_biography)"></div>
													<span class="glyphicon glyphicon-pencil" style="color:white;position: absolute;top: 3px;right: -25px;"></span>
												</div> -->
											</div>
										</div>
									</div>
									<div class="col-md-7 pad-about about-grid">
										<div ng-if="editAboutCtrl.isDropAvailable">
											<div class="photo-loader loader-about ng-class:{'setClassPhotoRequired': editAboutCtrl.setPhotosRequired}" ngf-drag-over-class="drag-over" ngf-drop ngf-select ngf-change="editAboutCtrl.uploadPhoto($files,$invalidFiles)" ngf-accept="'image/*'" ngf-drop-available="editAboutCtrl.isDropAvailable" ngf-multiple="true">
												<span>Enrich your about section with photos</span>
												<div class="plus-add">+</div>
												<span class="ng-class:{'setClassPhotoRequired': editAboutCtrl.setPhotosRequired}">Please upload a minimum of 3 and a maximum of 5 photos.</span>
											</div>
										</div>
										<div ng-if="editAboutCtrl.files.length > 0" ng-repeat="item in editAboutCtrl.files" style="max-height:200px; max-width:300px;">
											<div ng-if="item.progress <=100">
												<img ngf-thumbnail="item" style="max-height:200px; opacity:0.5;">
												<p>Uploading file: <span ng-bind="item.name"></span></p>
												<uib-progressbar value="item.progress" class="progress-striped active">{{item.progress}}</uib-progressbar>
											</div>
										</div>
										<div ngf-no-file-drop>
											<input type="file" name="file" ngf-select="editAboutCtrl.uploadPhoto($files, $invalidFiles)" ngf-accept="'image/*'" ngf-drop-available="editAboutCtrl.isDropAvailable" ngf-multiple="true">
										</div>
										<div class="item draggable-list about-edit-list" dnd-list="editAboutCtrl.images" ng-if="editAboutCtrl.images.length > 0" ng-cloak>
											<div class="col-sm-4 pad-about image-about-edit" ng-repeat="image in editAboutCtrl.images" dnd-draggable="image" dnd-effect-allowed="move" dnd-moved="editAboutCtrl.move($index)">
												<span class="button ion-crop crop-avatar-photo-icon" ng-click="editAboutCtrl.openCropModal(image.url, $index)"></span>
												<span class="ion-android-close x-close" ng-click="editAboutCtrl.deleteImage($index)"></span>
												<img ng-src="{{image.url}}" class="grid-image draggable-img">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>