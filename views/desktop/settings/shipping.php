<?php

use app\assets\desktop\settings\GlobalAsset;
use app\components\SettingsHeader;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = Yii::t('app/public', 'SHIPPING_SETTINGS');

$this->params['person'] = $person;
$this->params['settings_menu_active_option'] = 'shipping';
$this->registerJs('var person = ' .Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

<?php if($person->isCompletedProfile()) { ?>
	<?= SettingsHeader::widget() ?>
<?php } ?>

<div ng-controller="shippingSettingsCtrl as shippingSettingsCtrl" class="personal-info-wrapper bank-settings-wrapper panel-billing-send">
	<div class="container">
		<div ng-repeat="setting in shippingSettingsCtrl.person.shipping_settings" ng-cloak>
			<uib-accordion>
				<div uib-accordion-group class="panel-default panel-billing" ng-cloak is-open="shippingSettingsCtrl.country_helper[$index]['status']" is-disabled="true">
					<div uib-accordion-heading>
						<div class="row">
							<span ng-click="shippingSettingsCtrl.toggleStatus($index)" ng-bind="shippingSettingsCtrl.country_helper[$index].country_name" class="col-xs-6"></span>
							<span ng-click="shippingSettingsCtrl.toggleStatus($index)" ng-if="!shippingSettingsCtrl.country_helper[$index]['status']" class="col-xs-6">
								<span class="ion-edit red-icon pull-right" ng-if="setting.prices && setting.prices.length > 0" ng-cloak></span>
								<button class="btn btn-red btn-small" ng-if="!setting.prices || setting.prices.length <= 0" ng-cloak ><span translate="settings.shipping.ADD_SHIPPING_PRICES"></span></button>
							</span>
						</div>
					</div>
					<shipping-types setting="setting" currency="shippingSettingsCtrl.country_helper[$index].currency"></shipping-types>
					<hr>
					<shipping-weights-prices setting="setting" person="shippingSettingsCtrl.person" currency="shippingSettingsCtrl.country_helper[$index].currency"></shipping-weights-prices>
					<hr>
					<shipping-observations setting="setting" languages="shippingSettingsCtrl.languages"></shipping-observations>
					<div class="row text-center">
						<button class="btn btn-default btn-save btn-xl" ng-click="shippingSettingsCtrl.save()"><span translate="global.SAVE"></span></button>
						<?php if($person->isPublic()) { ?>
							<button class="btn btn-default btn-xl"><span translate="global.CANCEL"></span></button>
						<?php } ?>
					</div>
				</div>
			</uib-accordion>
		</div>
	</div>
</div>
