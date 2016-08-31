<?php
use app\models\Person;
use yii\web\View;
use app\models\Lang;
use app\assets\desktop\pub\Index2Asset;
use yii\helpers\Json;
use app\assets\desktop\deviser\EditPressAsset;

EditPressAsset::register($this);

/** @var Person $deviser */

$this->title = 'About ' . $deviser->getBrandName() . ' - Todevise';

?>

	<h2><?= $deviser->getBrandName() ?></h2>
	<div ng-controller="editPressCtrl as editPressCtrl" style="background: grey">
		<?php $this->registerJs("var _deviser = " . Json::encode($deviser) . ";", View::POS_HEAD); ?>
			<div ng-if="editPressCtrl.images === 0">You don't have any press images!</div>
			<form name="editPressCtrl.form">
				<div ng-if="editPressCtrl.isDropAvailable">
					<div ng-model="editPressCtrl.image" ngf-drop ngf-select ngf-accept="'image/*'" ngf-drop-available="editPressCtrl.isDropAvailable" style="width:200px; height: 200px; text-align: center; margin: 10px; border: 3px dashed white; background: grey;">Add press image</div>
				</div>
				<div ngf-no-file-drop>
					<input type="file" name="file" ng-model="editPressCtrl.image" ngf-select ngf-accept="'image/*'" ngf-drop-available="editPressCtrl.isDropAvailable">
					<button ng-click="editPressCtrl.upload(editPressCtrl.image)">Guardar</button>
				</div>
			</form>
			<div ng-if="editPressCtrl.images.length > 0" dnd-list="editPressCtrl.images">
				<img ng-repeat="item in editPressCtrl.images" ng-src="{{item.url}}" dnd-draggable="item" dnd-effect-allowed="move" dnd-moved="editPressCtrl.update($index)" style="width: 200px; max-height:300px; cursor:move; padding-left:20px;">
			</div>
	</div>