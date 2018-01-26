<?php

use app\assets\desktop\settings\GlobalAsset;
use app\components\SettingsHeader;
use app\models\Person;
use yii\helpers\Json;
use yii\helpers\Url;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = Yii::t('app/public', 'BILLINGS_AND_PAYMENTS');

$this->params['person'] = $person;
$this->params['settings_menu_active_option'] = 'billing';
$this->registerJs("var person = ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

<?php if($person->isCompletedProfile()) { ?>
	<?= SettingsHeader::widget() ?>
<?php } ?>

<div class="personal-info-wrapper bank-settings-wrapper">
	<div class="container">
		<uib-accordion>
			<div uib-accordion-group class="panel-default panel-billing" heading="Connect with your stripe account" is-open="true" ng-cloak>
				<?php if (empty($person->settingsMapping->stripeInfoMapping->access_token)) { ?>
					<div><?=Yii::t('app/public', 'PAYMENTS_MADE_TROUGH_STRIPE')?></div>
					<br />
					<div class="col-md-12 text-center">
						<a class="btn btn-red" href="<?=Url::to(['settings/connect-stripe', 'slug' => $person->slug, 'person_id' => $person->short_id])?>"><?=Yii::t('app/public', 'CONNECT_WITH_STRIPE')?></a>
					</div>
				<?php } else { ?>
					<div><?=Yii::t('app/public', 'PROFILE_AND_STRIPE_LINKED_INFO')?></div>
					<br />
					<div class="col-md-12 text-center">
						<a class="btn btn-red" href="<?=Url::to(['settings/connect-stripe', 'slug' => $person->slug, 'person_id' => $person->short_id])?>"><?=Yii::t('app/public', 'CONNECT_WITH_DIFFERENT_STRIPE_ACCOUNT')?></a>
					</div>
				<?php } ?>
			</div>
		</uib-accordion>
	</div>
</div>