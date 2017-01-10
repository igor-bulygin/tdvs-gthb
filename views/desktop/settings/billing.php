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

<div ng-controller="billingCtrl as billingCtrl" class="personal-info-wrapper container"> <!-- please change this class, is not semantic -->
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">
			<div class="panel-heading" role="tab" id="addressesHeading">
				<h2 class="panel-title">
					<a role="button" data-toggle="collapse" data-parent="#accordion" href="#addressesCollapse" aria-expanded="true" aria-controls="addressesCollapse">
						Addresses
					</a>
				</h2>
			</div>
			<div id="addressesCollapse" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="addressesHeading">
				<div class="panel-body">
					<form name="billingCtrl.addressesForm" class="form-horizontal">
						<div class="form-group">
							<label for="city" class="col-md-1">City</label>
							<div class="col-md-4">
								<input type="text" name="city" class="form-control" ng-model="billingCtrl.addresses.city">
							</div>
						</div>
						<div class="form-group">
							<label for="street" class="col-md-1">Street</label>
							<div class="col-md-2">
								<input type="text" name="street" class="form-control" ng-model="billingCtrl.addresses.street">
							</div>
							<label for="number" class="col-md-1">Number</label>
							<div class="col-md-1">
								<input type="text" name="number" class="form-control" ng-model="billingCtrl.addresses.number">
							</div>
							<label for="zipcode" class="col-md-1">ZIP</label>
							<div class="col-md-1">
								<input type="text" name="zipcode" class="form-control" ng-model="billingCtrl.addresses.zipcode">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<h1>Bank Information</h1>
	<h1>Payments</h1>
</div>