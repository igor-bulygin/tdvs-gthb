<?php
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Url;

app\components\assets\SettingsHeaderAsset::register($this);


// use params to share data between views :(
/** @var Person $person */
$person = $this->params['person'];

// use params to share data between views :(
$activeOption = array_key_exists('settings_menu_active_option', $this->params) ? $this->params['settings_menu_active_option'] : '';

?>

<div ng-controller="settingsHeaderCtrl as settingsHeaderCtrl">

<img src="<?= Utils::url_scheme() ?><?= Utils::thumborize($person->getAvatarImage())->resize(50, 50) ?>">

<?=$person->personalInfo->getBrandName()?>

<ul class="nav nav-tabs">
	<li role="presentation">
		<a class="<?= ($activeOption=='settings') ? 'active' : '' ?>" href="#">Settings</a>
	</li>
	<li role="presentation">
		<a class="<?= ($activeOption=='orders') ? 'active' : '' ?>" href="#">My Orders</a>
	</li>
	<li role="presentation">
		<a class="<?= ($activeOption=='stock') ? 'active' : '' ?>" href="#">Stock & Price</a>
	</li>
	<li role="presentation">
		<a class="<?= ($activeOption=='billing') ? 'active' : '' ?>" href="<?= Url::to(["settings/billing", "slug" => $person->slug, 'person_id' => $person->short_id])?>">Billing & Payments</a>
	</li>
	<li role="presentation">
		<a class="<?= ($activeOption=='shipping') ? 'active' : '' ?>" href="#">Shipping</a>
	</li>
	<li class="pull-right">
		<button class="btn btn-green" ng-click="settingsHeaderCtrl.saveChanges()">Save changes</button>
	</li>
</ul>


</div>