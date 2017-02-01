<?php
use app\assets\desktop\deviser\EditAboutAsset;
use app\components\DeviserAdminHeader;
use app\components\DeviserMakeProfilePublic;
use app\components\DeviserMenu;
use app\models\Person;

EditAboutAsset::register($this);

/** @var Person $deviser */

$this->title = 'About ' . $deviser->personalInfoMapping->getBrandName() . ' - Todevise';
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
												<div class="deviser-data-about-edit">
													<form class="grey-form" name="editAboutCtrl.form">
														<label for="fields">Choose your field(s) of work</label>
														<ol class="work-field nya-bs-select ng-class:{'error-input': editAboutCtrl.setCategoriesRequired}" ng-model="editAboutCtrl.deviser.categories" selected-text-format="count>4" multiple ng-cloak ng-if="editAboutCtrl.categories">
															<li nya-bs-option="category in editAboutCtrl.categories" data-value="category.id" deep-watch="true">
																<a href=""><span ng-bind="category.name"></span> <span class="glyphicon glyphicon-ok check-mark"></span></a>
															</li>
														</ol>
														<label for="text_biography">Brand Statement / Biography</label>
														<span class="small-grey">Translate your text by selecting different languages below.</span>
														<ol class="about-edit-select text-profile-lang nya-bs-select" ng-model="editAboutCtrl.biography_language" ng-cloak>
															<li nya-bs-option="language in editAboutCtrl.languages" data-value="language.code" deep-watch="true">
																<a href="">
																	<span ng-bind="language.name"></span> <span class="glyphicon glyphicon-ok check-mark"></span>
																</a>
															</li>
														</ol>
														<div class="textarea-edit-about ng-class:{'error-input': editAboutCtrl.setClassBiographyRequired}" text-angular ng-model="editAboutCtrl.deviser.text_biography[editAboutCtrl.biography_language]" ng-cloak ta-toolbar="[]" ta-paste="editAboutCtrl.stripHTMLTags($html)" placeholder="Write your brand statement / mission / biography."></div>
														<label class="pull-left" for="resume">Resume or brand presentation</label>
														<span class="optional-text pull-left">Optional</span>
														<span class="small-grey">Even more things to tell your customers? Upload it here.</span>
														<div class="edit-about-row">
															<button class="btn btn-default btn-green btn-upload-file" ngf-select="editAboutCtrl.uploadCV($file)" ngf-accept="'application/pdf,image/*'">UPLOAD FILE</button>
															<span class="cv" ng-if="editAboutCtrl.deviser.curriculum" ng-cloak>
															<span class="pull-left cv-file" ng-bind="editAboutCtrl.deviser.curriculum"></span> 
															<span class="remove-cv ion-ios-close-empty pull-left" ng-click="editAboutCtrl.deleteCV()"></span></span>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-7 pad-about about-grid">
										<div ng-if="editAboutCtrl.isDropAvailable">
											<div class="photo-loader loader-about ng-class:{'error-text': editAboutCtrl.setPhotosRequired}" ngf-drag-over-class="drag-over" ngf-drop ngf-select ngf-change="editAboutCtrl.uploadPhoto($files, $invalidFiles, null, true)" ngf-accept="'image/*'" ngf-drop-available="editAboutCtrl.isDropAvailable" ng-if="editAboutCtrl.images.length < 5">
												<span class="photo-loader-title">Enrich your about section with photos</span>
												<div class="plus-add-wrapper">
													<div class="plus-add">
														<span>+</span>
													</div>
													<div class="text">ADD PHOTOS</div>
												</div>
												<span class="photo-loader-warning ng-class:{'error-text': editAboutCtrl.setPhotosRequired}" ng-if="editAboutCtrl.images.length < 3 && editAboutCtrl.deviser.account_state==='draft'" ng-cloak>Please upload a minimum of 3 and a maximum of 5 photos.</span>
												<span class="photo-loader-warning" ng-if="editAboutCtrl.images.length < 3 && editAboutCtrl.deviser.account_state==='active'" ng-cloak>You need to have at least 3 photos to be able to SAVE CHANGES to your profile.</span>
											</div>
											<div class="photo-loader loader-about" ng-if="editAboutCtrl.images.length >= 5" ng-click="editAboutCtrl.checkPhotos()" ng-cloak>
												<span class="photo-loader-title">Enrich your about section with photos</span>
												<div class="plus-add-wrapper">
													<div class="plus-add">
														<span>+</span>
													</div>
													<div class="text">ADD PHOTOS</div>
												</div>
												<span class="photo-loader-warning" ng-if="editAboutCtrl.showMaxPhotosLimit">You can upload a maximum of 5 photos. Please delete 1 or more photos if you want to upload new ones.</span>
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
										<div class="item draggable-list about-edit-list" dnd-list="editAboutCtrl.images" ng-if="editAboutCtrl.images.length > 0" dnd-dragover="editAboutCtrl.dragOver(index)" ng-cloak>
											<div class="col-sm-6 pad-about image-about-edit" ng-repeat="image in editAboutCtrl.images track by $index" dnd-draggable="image" dnd-effect-allowed="move" dnd-dragstart="editAboutCtrl.dragStart($index)" dnd-canceled="editAboutCtrl.canceled()" dnd-moved="editAboutCtrl.moved($index)">
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