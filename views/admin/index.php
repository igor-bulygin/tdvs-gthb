<?php
/* @var $this yii\web\View */
$this->title = 'Todevise';
?>
<div class="site-index">

	<div class="body-content">

		<div class="row">
			<div class="col-lg-12">
				<?= Yii::t("app", "This is a test from {0} controller!", $this->context->id); ?>
				<?php
					if ($dd->isMobile()) {
						$device = "mobile";
					} else if($dd->isTablet()) {
						$device = "tablet";
					} else {
						$device = "desktop";
					}
				?>
				<br />
				<?= Yii::t("app", "Accessed from a {0}!", $device); ?>
			</div>
		</div>

	</div>

</div>
