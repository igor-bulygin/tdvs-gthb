<?php
use app\assets\desktop\admin\IndexAsset;

/* @var $this yii\web\View */

$this->params['breadcrumbs'][] = [
	'label' => 'Index',
	'url' => ['/admin/index']
];

IndexAsset::register($this);

$this->title = 'Todevise / Admin / Tags';
?>

<div class="row">
	<div class="col-lg-12">
		<?= Yii::t("app", "This is a test from {0} controller!", $this->context->id); ?>
	</div>
</div>
