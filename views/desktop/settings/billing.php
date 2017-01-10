<?php
use app\assets\desktop\settings\BillingAsset;
use app\components\SettingsHeader;
use app\models\Person;

BillingAsset::register($this);

/** @var Person $person */

$this->title = 'Billing & Payments - ' . $person->personalInfo->getBrandName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['settings_menu_active_option'] = 'billing';

?>

<?= SettingsHeader::widget() ?>

<div ng-controller="billingCtrl as billingCtrl" class="personal-info-wrapper"> <!-- please change class, is not semantic -->
	<h1>Addresses</h1>
	<h1>Bank Information</h1>
	<h1>Payments</h1>
</div>