<?php

use app\assets\desktop\admin\BannersAsset;

/* @var $this yii\web\View */
/* @var $countries ArrayObject */
/* @var $devisers yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = [
	'label' => 'Banners',
	'url' => ['/admin/banners']
];

BannersAsset::register($this);

$this->title = 'Todevise / Admin / Banners';
?>

Banners view