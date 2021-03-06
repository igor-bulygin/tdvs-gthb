<?php

use app\models\Person;

app\components\assets\SettingsHeaderAsset::register($this);


// use params to share data between views :(
/** @var Person $person */
$person = $this->params['person'];

// use params to share data between views :(
$activeOption = array_key_exists('settings_menu_active_option', $this->params) ? $this->params['settings_menu_active_option'] : '';

?>
<div ng-controller="settingsHeaderCtrl as settingsHeaderCtrl">
	<div class="upper-setting-header">
		<div class="container">
			<a href="<?=$person->getAboutLink()?>"><img class="avatar" src="<?= $person->getProfileImage(50, 50) ?>"></a>
			<a href="<?=$person->getAboutLink()?>"><span class="deviser-name"><?=$person->getName()?></span></a>
			<?php /*<button class="btn btn-green pull-right" ng-click="settingsHeaderCtrl.saveChanges()">Save changes</button>*/ ?>
		</div>
	</div>
	<ul class="nav nav-tabs header-settings-tabs no-border" style="justify-content: center; display: flex;">
		<li role="presentation" class="<?= ($activeOption=='general') ? 'active' : ''?> no-border">
			<a href="<?= $person->getSettingsLink('general')?>" class="height-100" translate="settings.header.GENERAL"></a>
		</li>
		<li role="presentation" class="<?= ($activeOption=='affiliates') ? 'active' : ''?> no-border">
			<a href="<?= $person->getSettingsLink('affiliates')?>" class="height-100" translate="settings.header.AFFILIATES_PROGRAM"></a>
		</li>
		<li role="presentation" class="<?= ($activeOption=='orders') ? 'active' : '' ?> no-border">
			<a href="<?= $person->getSettingsLink('open-orders')?>" class="height-100" translate="settings.header.MY_ORDERS"></a>
		</li>
		<?php if ($person->isDeviser()) { ?>
			<?php /* ?>
			<li role="presentation" class="<?= ($activeOption=='stock') ? 'active' : '' ?>">
				<a href="<?= $person->getSettingsLink('stock')" translate="settings.header.STOCK_PRICE"></a>
			</li>
			*/ ?>
			<li role="presentation" class="<?= ($activeOption=='billing') ? 'active' : '' ?> no-border">
				<a href="<?= $person->getSettingsLink('billing')?>" class="height-100" translate="settings.header.BILLING_PAYMENTS"></a>
			</li>
			<li role="presentation" class="<?= ($activeOption=='shipping') ? 'active' : '' ?> no-border">
				<a href="<?= $person->getSettingsLink('shipping')?>" class="height-100" translate="settings.header.SHIPPING"></a>
			</li>
		<?php } ?>
	</ul>

</div>

<script type="text/ng-template" id="changesSaved.html">
	<div class="modal-body">
		<span translate="CHANGES_SAVED"></span>
		<span class="pull-right" ng-click="$dismiss()">&times;</span>
	</div>
</script>
<script type="text/ng-template" id="invalidForm.html">
	<div class="modal-body">
		<span class="purple-text"><span translate="settings.header.PLS_CORRECT"></span></span>
		<span class="pull-right" ng-click="$dismiss()">&times;</span>
	</div>
</script>
