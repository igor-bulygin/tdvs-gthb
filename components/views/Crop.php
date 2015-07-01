<script type="text/ng-template" id="template/modal/global/crop.html">
	<form novalidate name="form">
		<div class='modal-header'>
			<h3 class='modal-title'><?php echo Yii::t("app/global", "Crop image"); ?></h3>
		</div>
		<div class='modal-body'>

			<div class="cropArea">
				<img-crop image="photo" result-image="croppedphoto"></img-crop>
			</div>

		</div>
		<div class='modal-footer'>
			<button class='btn btn-success' ng-click='form.$submitted = true; form.$valid && ok()'><?php echo Yii::t("app/global", "Confirm"); ?></button>
			<button class='btn btn-primary' ng-click='cancel()' type="submit"><?php echo Yii::t("app/global", "Cancel"); ?></button>
		</div>
	</form>
</script>
