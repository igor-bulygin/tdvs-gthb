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

<div ng-controller="billingCtrl as billingCtrl" class="personal-info-wrapper"> <!-- please change this class, is not semantic -->
	<div class="container">
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			<!-- <div class="panel panel-default">
				<div class="panel-heading" role="tab" id="addressesHeading">
					<h1 class="panel-title">
						<a role="button" data-toggle="collapse" data-parent="#accordion" href="#addressesCollapse" aria-expanded="true" aria-controls="addressesCollapse">
							Addresses
						</a>
					</h1>
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
				</div> -->
			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="bankInformationHeading">
					<h1 class="panel-title">
						<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#bankInformationCollapse" aria-expanded="false" aria-controls="bankInformationCollapse">Bank Information</a>
					</h1>
				</div>
				<div id="bankInformationCollapse" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="bankInformationHeading">
					<div class="panel-body">
						<form name="billingCtrl.bankInformationForm" class="form-horizontal">
							<div class="form-group">
								<label for="location" class="col-md-2">Location of bank</label>
								<div class="col-md-6">
									<ol class="nya-bs-select" ng-model="billingCtrl.bank_information.location" ng-change="billingCtrl.resetBankInfo(billingCtrl.bank_information.location)">
										<li nya-bs-option="location in billingCtrl.bank_location" data-value="location.country_code">
											<a href="" ng-bind="location.country_name"></a>
										</li>
									</ol>
								</div>
							</div>
							<canada-bank-information bank-information="billingCtrl.bank_information" ng-if="billingCtrl.bank_information.location === 'CA'"></canada-bank-information>
							<usa-bank-information bank-information="billingCtrl.bank_information" ng-if="billingCtrl.bank_information.location === 'US'"></usa-bank-information>
							<new-zealand-bank-information bank-information="billingCtrl.bank_information" ng-if="billingCtrl.bank_information.location === 'NZ'"></new-zealand-bank-information>
							<other-bank-information bank-information="billingCtrl.bank_information" ng-if="billingCtrl.bank_information.location === 'OTHER'"></other-bank-information>
						</form>
					</div>
				</div>
			</div>
	<!-- 		<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="paymentsHeading">
					<h1 class="panel-title">
						<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#paymentsCollapse" aria-expanded="false" aria-controls="paymentsCollapse">Payments</a>
					</h1>
				</div>
				<div id="paymentsCollapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="paymentsHeading">
					<div class="panel-body">
						
					</div>
				</div>
			</div> -->
		</div>
	</div>
	<div class="text-center">
		<button class="btn btn-green" ng-click="billingCtrl.saveBankInformation(billingCtrl.bankInformationForm)">Save</button>
	</div>
</div>