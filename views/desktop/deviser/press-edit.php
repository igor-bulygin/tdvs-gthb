<?php
use app\components\DeviserHeader;
use app\components\DeviserAdminHeader;
use app\components\DeviserMakeProfilePublic;
use app\components\DeviserMenu;
use app\models\Person;
use yii\web\View;
use app\models\Lang;
use app\assets\desktop\pub\Index2Asset;
use yii\helpers\Json;
use app\assets\desktop\deviser\EditPressAsset;

EditPressAsset::register($this);

/** @var Person $deviser */

$this->title = 'About ' . $deviser->personalInfo->getBrandName() . ' - Todevise';
$this->params['deviser'] = $deviser;
$this->params['deviser_menu_active_option'] = 'press';
$this->params['deviser_links_target'] = 'edit_view';

?>

	<?= DeviserAdminHeader::widget() ?>
	<?php if ($deviser->isDraft()) { ?>
		<?= DeviserMakeProfilePublic::widget() ?>
	<?php } ?>
		<?php $this->registerJs("var _deviser = " . Json::encode($deviser) . ";", View::POS_HEAD); ?>

			<div class="store" ng-controller="editPressCtrl as editPressCtrl">
				<div class="container">
					<div class="row">
						<div class="col-md-2">
							<?= DeviserMenu::widget() ?>
						</div>
						<div class="col-md-10">
							<div ng-if="editPressCtrl.files.length > 0" ng-repeat="item in editPressCtrl.files" style="max-height:200px; max-width:400px;">
								<div ng-if="item.progress <= 100">
									<img ngf-thumbnail="item" style="max-height:200px; opacity:0.5;">
									<p>Uploading file: {{item.name}}</p>
									<uib-progressbar max="100" value="item.progress" class="progress-striped active">{{item.progress}}</uib-progressbar>
								</div>
							</div>
							<div ng-if="editPressCtrl.images === 0" class="faq-edit-empty" ng-cloak>
								<img class="sad-face" src="/imgs/sad-face.svg">
								<p>You don't have any press images!</p>
							</div>
							<div class="press-edit-list row press-3">
								<form name="editPressCtrl.form" ng-cloak>
									<div class="col-sm-3" ng-if="editPressCtrl.isDropAvailable">
										<div class="photo-loader" class="menu-category list-group" ngf-drop ngf-select ngf-change="editPressCtrl.upload($files, $invalidFiles)" ngf-accept="'image/*'" ngf-drop-available="editPressCtrl.isDropAvailable" ngf-multiple="true">
											<div class="plus-add">+</div>
											<span>Add press image</span>
										</div>
									</div>
									<div ngf-no-file-drop>
										<input type="file" name="file" ngf-select="editPressCtrl.upload($files, $invalidFiles)" ngf-accept="'image/*'" ngf-drop-available="editPressCtrl.isDropAvailable" ngf-multiple="true">
									</div>
								</form>
								<div class="menu-category list-group draggable-list" ng-if="editPressCtrl.images.length > 0" dnd-list="editPressCtrl.images">
									<div class="col-sm-3" ng-repeat="item in editPressCtrl.images" dnd-draggable="item" dnd-effect-allowed="move" dnd-moved="editPressCtrl.update($index)">
										<span class="ion-android-close x-close" ng-click="editPressCtrl.deleteImage($index)"></span>
										<img style="width:100%; height:250px; overflow:hidden;" class="grid-image draggable-img" ng-src="{{item.url}}">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>