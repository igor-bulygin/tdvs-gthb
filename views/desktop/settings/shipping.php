<?php
use app\assets\desktop\settings\ShippingAsset;
use app\components\SettingsHeader;
use app\models\Person;
use yii\helpers\Json;
use yii\helpers\Url;

ShippingAsset::register($this);

/** @var Person $person */

$this->title = 'Shipping - ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['settings_menu_active_option'] = 'shipping';
$this->registerJs('var person = ' .Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

<?= SettingsHeader::widget() ?>

<div ng-controller="shippingSettingsCtrl as shippingSettingsCtrl" class="personal-info-wrapper bank-settings-wrapper">
	<div class="container">
		<uib-accordion close-others="false">
			<shipping-zones></shipping-zones>
			<shipping-weights></shipping-weights>
			<shipping-prices></shipping-prices>
			<shipping-observations></shipping-observations>
		</uib-accordion>
	</div>
</div>