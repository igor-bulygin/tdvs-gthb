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
	<div ng-controller="editPressCtrl as editPressCtrl">
		<?php $this->registerJs("var _deviser = " . Json::encode($deviser) . ";", View::POS_HEAD); ?>
			<div>You don't have any press images!</div>
			<form name="editPressCtrl.form">
				<input type="file" ng-model="editPressCtrl.image" ngf-select name="file">
				<button ng-click="editPressCtrl.upload(editPressCtrl.form)">Guardar</button>
			</form>
	</div>