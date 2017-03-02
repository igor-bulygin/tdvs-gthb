<?php
use app\assets\desktop\settings\BillingAsset;
use app\components\SettingsHeader;
use app\models\Person;
use yii\helpers\Url;
use yii\helpers\Json;

BillingAsset::register($this);

/** @var Person $person */

$this->title = 'Billing & Payments - ' . $person->personalInfoMapping->getBrandName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['settings_menu_active_option'] = 'billing';
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

<?= SettingsHeader::widget() ?>

<div ng-controller="billingCtrl as billingCtrl" class="personal-info-wrapper bank-settings-wrapper"> <!-- please change this class, is not semantic -->
	<div class="container">
		<uib-accordion>
			<div uib-accordion-group class="panel-default panel-billing" heading="Addresses" is-disabled="true" ng-cloak>
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
					</div>
					<div class="form-group">
						<label for="number" class="col-md-1">Number</label>
						<div class="col-md-1">
							<input type="text" name="number" class="form-control" ng-model="billingCtrl.addresses.number">
						</div>
					</div>
					<div class="form-group">
						<label for="zipcode" class="col-md-1">ZIP</label>
						<div class="col-md-1">
							<input type="text" name="zipcode" class="form-control" ng-model="billingCtrl.addresses.zipcode">
						</div>
					</div>
				</form>
			</div>
            <?php /*
			<div uib-accordion-group class="panel-default panel-billing" heading="Bank Information" is-disabled="true" ng-cloak>
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
					<australia-bank-information bank-information="billingCtrl.bank_information" errors="billingCtrl.errors" ng-if="billingCtrl.bank_information.location === 'AU'"></australia-bank-information>
					<canada-bank-information bank-information="billingCtrl.bank_information" errors="billingCtrl.errors" ng-if="billingCtrl.bank_information.location === 'CA'"></canada-bank-information>
					<usa-bank-information bank-information="billingCtrl.bank_information" errors="billingCtrl.errors" ng-if="billingCtrl.bank_information.location === 'US'"></usa-bank-information>
					<new-zealand-bank-information bank-information="billingCtrl.bank_information" errors="billingCtrl.errors" ng-if="billingCtrl.bank_information.location === 'NZ'"></new-zealand-bank-information>
					<other-bank-information bank-information="billingCtrl.bank_information" errors="billingCtrl.errors" ng-if="billingCtrl.bank_information.location === 'OTHER'"></other-bank-information>
				</form>
            </div>
            */ ?>
            <div uib-accordion-group class="panel-default panel-billing" heading="Connect with your stripe account" is-open="true" ng-cloak>
                <?php if (empty($person->settingsMapping->stripeInfoMapping->access_token)) { ?>
                    <div>All the payments on Todevise are made through Stripe, a payment processing platform. We use Stripe because it is very secure, fast and easy to use. The Stripe commission is 1,40% + 0,25€ for each transaction. The money will be transfered to your Stripe account immediately after each purchase. <br /><br/>To start selling on Todevise, you must open a Stripe account (it’s 100% free) and connect it to your Todevise profile. To do so, press the button below. When you finish creating your Stripe account, you will be redirected back to this page.</div>
                    <br />
                    <div class="col-md-12 text-center">
                        <a class="btn btn-default btn-green" href="<?=Url::to(['settings/connect-stripe', 'slug' => $person->slug, 'person_id' => $person->short_id])?>">Connect with stripe</a>
                    </div>
                <?php } else { ?>
                    <div>Your Todevise profile and Stripe account are now linked. Do you want to connect your profile to a different Stripe account? Press the button below.</div>
                    <br />
                    <div class="col-md-12 text-center">
                        <a class="btn btn-default btn-green" href="<?=Url::to(['settings/connect-stripe', 'slug' => $person->slug, 'person_id' => $person->short_id])?>">Connect with a different stripe account</a>
                    </div>
                <?php } ?>
            </div>
			<div uib-accordion-group class="panel-default panel-billing" heading="Payments" is-disabled="true" ng-cloak>
				<div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fugiat soluta maxime incidunt similique quo fuga eum neque sunt, voluptatibus! Corrupti rerum voluptate sunt, officia fugit, iste velit aliquam animi itaque.</div>
				<div>Dolores deleniti, distinctio nihil deserunt possimus expedita veritatis doloribus consequatur a facere unde aliquid similique non dolor dolore animi placeat, sunt earum sint laudantium quas? Perferendis sit quibusdam laboriosam aliquam.</div>
				<div>Nemo nobis nihil, a recusandae obcaecati id, amet porro eum, excepturi perferendis autem quam fugit dicta nisi facilis iste sint laudantium. Vel quis doloribus nemo, quidem, reprehenderit nisi vitae ea.</div>
			</div>
		</uib-accordion>
	</div>
</div>