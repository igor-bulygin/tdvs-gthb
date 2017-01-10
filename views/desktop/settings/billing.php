<?php
use app\assets\desktop\pub\PublicCommonAsset;
use app\components\SettingsHeader;
use app\models\Person;

PublicCommonAsset::register($this);

/** @var Person $person */

$this->title = 'Billing & Payments - ' . $person->personalInfo->getBrandName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['settings_menu_active_option'] = 'billing';

?>

<?= SettingsHeader::widget() ?>

Billing & Payments