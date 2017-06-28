<?php
use app\assets\desktop\settings\GlobalAsset;
use app\components\SettingsHeader;
use app\models\Person;
use yii\helpers\Json;
use yii\helpers\Url;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = 'Billing & Payments - ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['settings_menu_active_option'] = 'billing';
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

<?= SettingsHeader::widget() ?>

<div class="personal-info-wrapper bank-settings-wrapper">
	<div class="container">
		<uib-accordion>
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
		</uib-accordion>
	</div>
</div>