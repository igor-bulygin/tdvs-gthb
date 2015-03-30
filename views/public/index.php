<?php

use yii\helpers\Utils;

/* @var $this yii\web\View */
$this->title = 'Todevise';
?>
<div class="site-index">

	<div class="body-content">

		<div class="row">
			<div class="col-lg-12">
				<?= Yii::t("app", "This is a test from {0} controller!", $this->context->id); ?>
				<?php
					$device = $dd->isMobile() && !$dd->isTablet() ? "mobile" :
						$dd->isTablet() ? "tablet" :
							"desktop machine";
				?>
				<br />
				<?= Yii::t("app", "Accessed from a {0}!", $device); ?>
				<br />
				<?= Utils::shortID(6); ?>
			</div>
		</div>

	</div>

</div>
