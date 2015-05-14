<?php
use app\assets\desktop\pub\IndexAsset;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Index',
	'url' => ['/public/index']
];

IndexAsset::register($this);

$this->title = 'Todevise / Public / Index';
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
