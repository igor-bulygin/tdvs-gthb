<?php
use app\components\assets\cropAsset;

cropAsset::register($this);

?>

<script type="text/ng-template" id="template/modal/deviser/crop.html">
	<form novalidate name="form">
		<div class='modal-header'>
			<h3 class='modal-title'><?= Yii::t("app/deviser", "Crop image"); ?></h3>
		</div>
		<div class='modal-body'>

			<div class="cropArea">
				<img-crop area-type="square" image="photo" result-image="croppedphoto"></img-crop>
			</div>

		</div>
		<div class='modal-footer'>
			<button class='btn btn-success' ng-click='form.$submitted = true; form.$valid && ok()'><?= Yii::t("app/deviser", "Confirm"); ?></button>
			<button class='btn btn-primary' ng-click='cancel()' type="submit"><?= Yii::t("app/deviser", "Cancel"); ?></button>
		</div>
	</form>
</script>

<script type="text/ng-template" id="template/modal/deviser/crop_circle.html">
	<form novalidate name="form">
		<div class='modal-header'>
			<h3 class='modal-title'><?= Yii::t("app/deviser", "Crop image"); ?></h3>
		</div>
		<div class='modal-body'>

			<div class="cropArea">
				<img-crop area-type="circle" image="photo" result-image="croppedphoto"></img-crop>
			</div>

		</div>
		<div class='modal-footer'>
			<button class='btn btn-success' ng-click='form.$submitted = true; form.$valid && ok()'><?= Yii::t("app/deviser", "Confirm"); ?></button>
			<button class='btn btn-primary' ng-click='cancel()' type="submit"><?= Yii::t("app/deviser", "Cancel"); ?></button>
		</div>
	</form>
</script>

<script type="text/ng-template" id="template/modal/deviser/crop_rectangle.html">
	<form novalidate name="form">
		<div class='modal-header'>
			<h3 class='modal-title'><?= Yii::t("app/deviser", "Crop image"); ?></h3>
		</div>
		<div class='modal-body'>

			<div class="cropArea">
				<img-crop area-type="rectangle" image="photo" result-image="croppedphoto"></img-crop>
			</div>

		</div>
		<div class='modal-footer'>
			<button class='btn btn-success' ng-click='form.$submitted = true; form.$valid && ok()'><?= Yii::t("app/deviser", "Confirm"); ?></button>
			<button class='btn btn-primary' ng-click='cancel()' type="submit"><?= Yii::t("app/deviser", "Cancel"); ?></button>
		</div>
	</form>
</script>
