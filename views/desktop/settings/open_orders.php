<?php
use app\assets\desktop\settings\GlobalAsset;
use app\components\SettingsHeader;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = 'My orders - Open orders - ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['settings_menu_active_option'] = 'orders';
$this->registerJs('var person = ' .Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

<?php if($person->isPublic()) { ?>
	<?= SettingsHeader::widget() ?>
<?php } ?>

<div ng-controller="openOrdersCtrl as openOrdersCtrl">
	<div class="container">
		<uib-accordion>
			<div uib-accordion-group ng-cloak ng-repeat="order in openOrdersCtrl.orders">
				<div uib-accordion-heading>
					<span>Order nº</span>
					<span ng-bind="order.id"></span>
				</div>
				<div class="col-md-12">
					<h4>Info</h4>
					<div class="col-md-6">
						<div class="col-md-4">
							<p>Price</p>
							<p ng-bind="order.subtotal"></p>
						</div>
						<div class="col-md-4">
							<p>Client info</p>
							<p ng-bind="order.person_info.first_name + ' ' + order.person_info.last_name"></p>
							<p ng-bind="order.person_info.address"></p>
							<p ng-bind="order.person_info.city + ', ' + order.person_info.country"></p>
						</div>
						<div class="col-md-4">
							<p>Shipping</p>
						</div>
					</div>
					<div class="col-md-6">
						<!-- tabs -->
					</div>
				</div>
			</div>
		</uib-accordion>
	</div>
</div>