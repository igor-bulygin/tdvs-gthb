<?php

use app\components\assets\cropAsset;

cropAsset::register($this);

?>

<script type="text/ng-template" id="template/modal/deviser/crop.html">
	<form novalidate name="form">
		<div class='modal-header'>
			<h3 class='modal-title'><?= Yii::t("app/public", "Crop image"); ?></h3>
		</div>
		<div class='modal-body'>

			<div class="cropArea">
				<img-crop area-type="square" image="photo" result-image="croppedphoto"></img-crop>
			</div>

		</div>
		<div class='modal-footer'>
			<button class='btn btn-success' ng-click='form.$submitted = true; form.$valid && ok()'><?= Yii::t("app/public", "Confirm"); ?></button>
			<button class='btn btn-primary' ng-click='cancel()' type="submit"><?= Yii::t("app/public", "Cancel"); ?></button>
		</div>
	</form>
</script>

<script type="text/ng-template" id="template/modal/deviser/crop_circle.html">
	<form novalidate name="form">
		<div class='modal-header'>
			<h3 class='modal-title'><?= Yii::t("app/public", "Crop image"); ?></h3>
		</div>
		<div class='modal-body'>

			<div class="cropArea">
				<img-crop area-type="circle" result-image-size="{w:400, h:400}" image="photo" result-image="croppedphoto"></img-crop>
			</div>

		</div>
		<div class='modal-footer'>
			<button class='btn btn-success' ng-click='form.$submitted = true; form.$valid && ok()'><?= Yii::t("app/public", "Confirm"); ?></button>
			<button class='btn btn-primary' ng-click='cancel()' type="submit"><?= Yii::t("app/public", "Cancel"); ?></button>
		</div>
	</form>
</script>

<script type="text/ng-template" id="template/modal/deviser/crop_rectangle.html">
	<form novalidate name="form">
		<div class='modal-header'>
			<h3 class='modal-title'><?= Yii::t("app/public", "Crop image"); ?></h3>
		</div>
		<div class='modal-body'>

			<div class="cropArea">
				<img-crop area-type="rectangle" result-image-size="{w:1280, h:425}" aspect-ratio="3" image="photo" result-image="croppedphoto"></img-crop>
			</div>

		</div>
		<div class='modal-footer'>
			<button class='btn btn-success' ng-click='form.$submitted = true; form.$valid && ok()'><?= Yii::t("app/public", "Confirm"); ?></button>
			<button class='btn btn-primary' ng-click='cancel()' type="submit"><?= Yii::t("app/public", "Cancel"); ?></button>
		</div>
	</form>
</script>
