<?php
use app\assets\desktop\deviser\IndexAsset;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Index',
	'url' => ['/deviser/index']
];

IndexAsset::register($this);

$this->title = 'Todevise / Deviser / Index';
?>
<div class="site-index">

	<div class="body-content">

		<div class="row">
			<div class="col-lg-12">
				<?= Yii::t("app", "This is a test from {0} controller!", $this->context->id); ?>
			</div>
		</div>

	</div>

</div>
