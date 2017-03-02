<?php
use app\assets\desktop\deviser\EditPressAsset;
use app\components\DeviserMakeProfilePublic;
use app\components\DeviserMenu;
use app\components\PersonHeader;
use app\models\Person;
use yii\helpers\Json;
use yii\web\View;

EditPressAsset::register($this);

/** @var Person $person */

$this->title = 'About ' . $person->personalInfoMapping->getBrandName() . ' - Todevise';
$this->params['deviser'] = $person;
$this->params['person'] = $person;
$this->params['deviser_menu_active_option'] = 'press';
$this->params['deviser_links_target'] = 'edit_view';

?>

	<?= PersonHeader::widget() ?>
	<?php if ($person->isDraft()) { ?>
		<?= DeviserMakeProfilePublic::widget() ?>
	<?php } ?>
		<?php $this->registerJs("var _deviser = " . Json::encode($person) . ";", View::POS_HEAD); ?>

			<div class="store" ng-controller="editPressCtrl as editPressCtrl">
				<div class="container">
					<div class="row">
						<div class="col-md-2">
							<?= DeviserMenu::widget() ?>
						</div>
						<div class="col-md-10">
							<div class="empty-wrapper">
							<div><a href="#" ng-click="editPressCtrl.done()" class="red-link-btn" ng-if="editPressCtrl.images.length>0">I'm done editing press</a></div>
							<div ng-if="editPressCtrl.files.length > 0" ng-repeat="item in editPressCtrl.files" style="max-height:200px; max-width:400px;">
								<div ng-if="item.progress <= 100" ng-cloak>
									<img ngf-thumbnail="item" style="max-height:200px; opacity:0.5;">
									<p>Uploading file: {{item.name}}</p>
									<uib-progressbar max="100" value="item.progress" class="progress-striped active">{{item.progress}}</uib-progressbar>
								</div>
							</div>
							<div ng-if="editPressCtrl.images.length === 0" class="faq-edit-empty" ng-cloak>
								<img class="sad-face" src="/imgs/sad-face.svg">
								<p>You don't have any press images!</p>
								<button class="btn btn-default btn-green" ngf-select="editPressCtrl.upload($files, $invalidFiles)" ngf-accept="'image/*'" ngf-multiple="true">Add press</button>
							</div>
							<div class="row press-3" ng-if="editPressCtrl.images.length > 0" ng-cloak>
								<form name="editPressCtrl.form" ng-cloak>
									<div class="col-sm-6 h250" ng-if="editPressCtrl.isDropAvailable">
										<div class="photo-loader-press" class="menu-category list-group" ngf-drop ngf-select ngf-change="editPressCtrl.upload($files, $invalidFiles)" ngf-accept="'image/*'" ngf-drop-available="editPressCtrl.isDropAvailable" ngf-multiple="true">
											<span class="photo-loader-title">SHOW WHAT THE PRESS HAS TO SAY ABOUT YOU</span>
											<div class="plus-add-wrapper">
												<div class="plus-add">
													<span>+</span>
												</div>
												<div class="text">ADD PHOTOS</div>
											</div>
											<span class="photo-loader-warning">In public mode, the photos will be shown in their full size.</span>
										</div>
									</div>
									<div ngf-no-file-drop>
										<input type="file" name="file" ngf-select="editPressCtrl.upload($files, $invalidFiles)" ngf-accept="'image/*'" ngf-drop-available="editPressCtrl.isDropAvailable" ngf-multiple="true">
									</div>
								</form>
								<div class="list-group draggable-list" ng-if="editPressCtrl.images.length > 0" dnd-list="editPressCtrl.images" dnd-dragover="editPressCtrl.dragOver(index)" ng-cloak>
									<div class="col-sm-3" ng-repeat="item in editPressCtrl.images track by $index" dnd-type="'pressType'" dnd-draggable="item" dnd-effect-allowed="move" dnd-dragstart="editPressCtrl.dragStart($index)" dnd-canceled="editPressCtrl.canceled()" dnd-moved="editPressCtrl.moved($index)">
										<div class="image-press-edit">
											<span class="ion-android-close x-close" style="background-color: black" ng-click="editPressCtrl.deleteImage($index)"></span>
											<img class="grid-image draggable-img" ng-src="{{item.url}}">
										</div>
									</div>
								</div>
							</div>
						</div>
						</div>
					</div>
				</div>
			</div>