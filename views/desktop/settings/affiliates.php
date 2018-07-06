<?php

use app\assets\desktop\settings\GlobalAsset;
use app\components\SettingsHeader;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */

$this->title = Yii::t('app/public', 'SETTINGS');

$this->params['person'] = $person;
$this->params['settings_menu_active_option'] = 'affiliates';
$this->registerJs("var person= ".Json::encode($person), yii\web\View::POS_HEAD, 'person-var-script');

?>

<?php if($person->isCompletedProfile()) { ?>
<?= SettingsHeader::widget() ?>
<?php } ?>

<div ng-controller="generalSettingsCtrl as generalSettingsCtrl" class="personal-info-wrapper general-settings-wrapper">
	<div class="container">
		<div class="col-md-12 no-pad">
			<div class="col-md-6 col-sm-6 col-xs-12 no-pad">
				<div class="unique-affiliates-code-title"><span translate="settings.general.UNIQUE_AFFILIATE_CODE"></span> </div>
				<div class="rectangle-value affiliate-unique-code"><?= $person->affiliate_id; ?></div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12 no-pad">
				<span translate="settings.general.UNIQUE_AFFILIATE_CODE_EXPLANATION"></span>
			</div>
		</div>

		<div class="col-md-12 no-pad block-separator-full"></div>

		<div class="col-md-12 no-pad">
			<div class="col-md-6 col-sm-6 col-xs-12 no-pad">
				<div class="line-title-only col-md-12 no-pad"><span translate="settings.general.EARNINGS"></span></div>
				<div class="general-title-text col-md-6 no-pad"><span translate="settings.general.NUMBER_OF_AFFILIATES"></span> </div>
				<?php $has_affiliates = count($affiliates); ?>
				<?php if ($has_affiliates) { ?>
					<div class="rectangle-value result-info-rectangular-clickable col-md-6"><?= $has_affiliates; ?></div>
				<?php } else { ?>
					<div class="rectangle-value result-info-rectangular col-md-6"><?= $has_affiliates; ?></div>
				<?php } ?>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12 no-pad">

			</div>
		</div>

		<div class="col-md-12 no-pad block-separator-full"></div>

	</div>
</div>
