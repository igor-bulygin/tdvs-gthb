<?php

use app\assets\desktop\settings\GlobalAsset;
use app\components\SettingsHeader;
use app\models\Person;
use yii\helpers\Json;
use yii\helpers\Url;

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

<div ng-controller="affiliatesSettingsCtrl as affiliatesSettingsCtrl" class="personal-info-wrapper general-settings-wrapper">
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
        <div class="col-md-12 no-pad block-separator-light"></div>
				<div class="general-title-text col-md-6 no-pad"><span translate="settings.general.NUMBER_OF_AFFILIATES"></span> </div>
				<?php $has_affiliates = count($affiliates); ?>

        <?php if ($has_affiliates) { ?>
          <div class="rectangle-value result-info-rectangular-clickable col-md-6" ng-click="affiliatesSettingsCtrl.showHistoricFn()"><?= $has_affiliates; ?></div>
          <div id="historic_affiliates" ng-show="affiliatesSettingsCtrl.showHistoric">
            <div class="tools"><div class="close" ng-click="affiliatesSettingsCtrl.showHistoricFn()"><span class="ion-close-circled close-black"></span></div></div>
            <span class="affiliate-title-table" translate="settings.general.MEMBERS_IN_YOUR_NETWORK"></span>
            <div class="row no-margin">
              <div class="col-xs-8 no-pad">
                <span class="affiliate-title-column-table" translate="settings.general.NAME"></span>
              </div>
              <div class="col-xs-4 no-pad">
                <span class="affiliate-title-column-table" translate="settings.general.MADE_YOU"></span>
              </div>
            </div>
            <div class="earnings_container">
              <?php foreach ($affiliates as $affiliate) { ?>
                <div class="row no-margin">
                  <div class="col-xs-8 no-pad">
                    <span><a class="historic_link" href="<?php echo $affiliate['mainLink']; ?>" target="_blank"><?php echo $affiliate['fullName']; ?></a></span>
                  </div>
                  <div class="col-xs-4 no-pad">
                    <span><?php echo $affiliate['totalEarning']; ?>€</span>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        <?php } else { ?>
            <div class="rectangle-value result-info-rectangular col-md-6"><?= $has_affiliates; ?></div>
        <?php } ?>

        <div class="col-md-12 no-pad block-separator-light"></div>
        <div class="general-title-text col-md-6 no-pad"><span translate="settings.general.MONEY_WON_SO_FAR"></span> </div>
        <div class="rectangle-value result-info-rectangular col-md-6"><?= (isset($person->total_won_so_far)) ? $person->total_won_so_far : '0'; ?>€</div>


        <?php if($person->isClient()) { ?>
          <div class="col-md-12 no-pad block-separator-light"></div>
          <div class="general-title-text col-md-6 no-pad">
            <div class="general-title-text col-md-6 no-pad"><span translate="settings.general.MONEY_AVAILABLE"></span></div>
            <div class="rectangle-value result-info-rectangular col-md-6"><?= (isset($person->available_earnings)) ? $person->available_earnings : '0'; ?>€ </div>
          </div>
          <div class="col-md-12 no-pad block-separator-light"></div>
          <!-- <div class="general-title-text col-md-6 no-pad">
            <span class="grey-text" translate="settings.general.SPEND_MONEY"></span>
          </div> -->
        <?php } ?>
			</div>

			<div class="col-md-6 col-sm-6 col-xs-12 no-pad bank-account-container">

        <div class="col-xs-12 hidden-sm hidden-md hidden-lg no-pad block-separator-full"></div>

        <!-- <?php //if($person->isDeviser()) { ?>

          <?php //if (empty($person->settingsMapping->stripeInfoMapping->access_token)) { ?>
            <div class="line-title-only col-md-12 no-pad"><span translate="settings.general.BANK_ACCOUNT"></span></div>
            <div class="col-md-12 no-pad"><span translate="settings.general.IBAN"></span> </div>
            <div class="col-md-12 no-pad"><span translate="settings.general.FILL_IBAN_NUMBER_TEXT"></span> </div>

            <div class="col-md-12 no-pad block-separator-light"></div>

            <form name="affiliatesSettingsCtrl.dataForm" class="form-horizontal" ng-show="!affiliatesSettingsCtrl.saving">
              <input class="form-control iban-input iban-input-first" id="iban_0" type="text" maxlength="4" ng-readonly="!affiliatesSettingsCtrl.editMode" ng-keyup="keyUpHandler($event, 1)" name="iban0" ng-paste="pasteIban($event)" ng-model="affiliatesSettingsCtrl.person.personal_info.iban0">
              <input class="form-control iban-input" id="iban_1" type="text" maxlength="4" ng-readonly="!affiliatesSettingsCtrl.editMode" ng-keyup="keyUpHandler($event, 2)" name="iban1" ng-paste="pasteIban($event)" ng-model="affiliatesSettingsCtrl.person.personal_info.iban1">
              <input class="form-control iban-input" id="iban_2" type="text" maxlength="4" ng-readonly="!affiliatesSettingsCtrl.editMode" ng-keyup="keyUpHandler($event, 3)" name="iban2" ng-paste="pasteIban($event)" ng-model="affiliatesSettingsCtrl.person.personal_info.iban2">
              <input class="form-control iban-input" id="iban_3" type="text" maxlength="4" ng-readonly="!affiliatesSettingsCtrl.editMode" ng-keyup="keyUpHandler($event, 4)" name="iban3" ng-paste="pasteIban($event)" ng-model="affiliatesSettingsCtrl.person.personal_info.iban3">
              <input class="form-control iban-input" id="iban_4" type="text" maxlength="4" ng-readonly="!affiliatesSettingsCtrl.editMode" ng-keyup="keyUpHandler($event, 5)" name="iban4" ng-paste="pasteIban($event)" ng-model="affiliatesSettingsCtrl.person.personal_info.iban4">
              <input class="form-control iban-input" id="iban_5" type="text" maxlength="4" ng-readonly="!affiliatesSettingsCtrl.editMode" name="iban5" ng-paste="pasteIban($event)" ng-model="affiliatesSettingsCtrl.person.personal_info.iban5">
              <input class="form-control iban-input" style="clear:both; width: 100%;"  type="hidden" name="iban" ng-model="affiliatesSettingsCtrl.person.personal_info.iban">
              <div class="col-xs-12 no-pad error-info" ng-if="affiliatesSettingsCtrl.saved&&!affiliatesSettingsCtrl.dataForm.$dirty">
                <span translate="settings.general.CHANGES_SAVED"><i class="ion-checkmark text-red"></i></span>
              </div>
              <div class="col-xs-12 no-pad error-info" ng-if="affiliatesSettingsCtrl.showErrors">
                <span translate="settings.general.INCORRECT_IBAN"></span>
              </div>
              <div class="col-md-12 no-pad block-separator-light"></div>
              <div class="col-md-12 mt-20 text-center">
              <button class="btn btn-red btn-small auto-center" ng-click="affiliatesSettingsCtrl.update()" ng-if="affiliatesSettingsCtrl.editMode" translate="settings.SAVE_IBAN"></button>
                <?php //if (!array_key_exists("iban", $person['personal_info']) || empty($person['personal_info']['iban'])): ?>
                  <button class="btn btn-small btn-black-on-white" ng-click="affiliatesSettingsCtrl.changeModeFn()" ng-if="!affiliatesSettingsCtrl.editMode && !affiliatesSettingsCtrl.success" translate="settings.ADD_IBAN"></button>
                  <button class="btn btn-small btn-black-on-white" ng-click="affiliatesSettingsCtrl.changeModeFn()" ng-if="!affiliatesSettingsCtrl.editMode && affiliatesSettingsCtrl.success" translate="settings.EDIT_IBAN"></button>
                <?php //else: ?>
                  <button class="btn btn-small btn-black-on-white" ng-click="affiliatesSettingsCtrl.changeModeFn()" ng-if="!affiliatesSettingsCtrl.editMode" translate="settings.EDIT_IBAN"></button>
                <?php //endif;?>
              </div>
            </form>
          <?php //} ?>

        <?php //} ?> -->

        <?php if($person->isInfluencer()) { ?>

          <div class="line-title-only col-md-12 no-pad"><span translate="settings.general.OPTIONS_TO_RECEIVE_MONEY"></span></div>

          <div class="col-md-12 no-pad block-separator-light"></div>

          <div class="col-md-12 no-pad">
            <span class="option-number">1</span><span class="text-16 bold uppercase" translate="settings.general.STRIPE"></span><span class="text-grey uppercase" translate="settings.general.RECOMMENDED"></span><br/>
            <span class="text-pad-35" translate="settings.general.STRIPE_DESCRIPTION"></span>
          </div>

          <div class="col-md-12 no-pad block-separator-light"></div>
          <div class="col-md-12 no-pad block-separator-light"></div>

          <?php if (empty($person->settingsMapping->stripeInfoMapping->access_token)) { ?>
  					<br />
  					<div class="col-md-12 text-center">
  						<a class="btn btn-red btn-small" href="<?=Url::to(['settings/connect-stripe', 'slug' => $person->slug, 'person_id' => $person->short_id])?>"><?=Yii::t('app/public', 'CONNECT_WITH_STRIPE')?></a>
  					</div>
  				<?php } else { ?>
  					<br />
  					<div class="col-md-12 text-center">
  						<a class="btn btn-small btn-black-on-white" href="<?=Url::to(['settings/connect-stripe', 'slug' => $person->slug, 'person_id' => $person->short_id])?>"><?=Yii::t('app/public', 'EDIT_STRIPE_ACCOUNT')?></a>
  					</div>
  				<?php } ?>

          <!-- <?php //if (empty($person->settingsMapping->stripeInfoMapping->access_token)) { ?>
            <div class="col-md-12 no-pad block-separator-light"></div>
            <div class="col-md-12 no-pad block-separator-light"></div>
            <div class="col-md-12 no-pad block-separator-light"></div>
            <div class="col-md-12 no-pad block-separator-light"></div>

            <div class="col-md-12 no-pad">
              <span class="option-number">2</span><span class="text-16 bold uppercase" translate="settings.general.IBAN"></span><br/>
              <span class="text-pad-35" translate="settings.general.FILL_IBAN_NUMBER_TEXT"></span>
            </div>

            <div class="col-md-12 no-pad block-separator-light"></div>

            <form name="affiliatesSettingsCtrl.dataForm" class="form-horizontal influencer" ng-show="!affiliatesSettingsCtrl.saving">
              <input class="form-control iban-input iban-input-first" id="iban_0" type="text" maxlength="4" ng-readonly="!affiliatesSettingsCtrl.editMode" ng-keyup="keyUpHandler($event, 1)" name="iban0" ng-paste="pasteIban($event)" ng-model="affiliatesSettingsCtrl.person.personal_info.iban0">
              <input class="form-control iban-input" id="iban_1" type="text" maxlength="4" ng-readonly="!affiliatesSettingsCtrl.editMode" ng-keyup="keyUpHandler($event, 2)" name="iban1" ng-paste="pasteIban($event)" ng-model="affiliatesSettingsCtrl.person.personal_info.iban1">
              <input class="form-control iban-input" id="iban_2" type="text" maxlength="4" ng-readonly="!affiliatesSettingsCtrl.editMode" ng-keyup="keyUpHandler($event, 3)" name="iban2" ng-paste="pasteIban($event)" ng-model="affiliatesSettingsCtrl.person.personal_info.iban2">
              <input class="form-control iban-input" id="iban_3" type="text" maxlength="4" ng-readonly="!affiliatesSettingsCtrl.editMode" ng-keyup="keyUpHandler($event, 4)" name="iban3" ng-paste="pasteIban($event)" ng-model="affiliatesSettingsCtrl.person.personal_info.iban3">
              <input class="form-control iban-input" id="iban_4" type="text" maxlength="4" ng-readonly="!affiliatesSettingsCtrl.editMode" ng-keyup="keyUpHandler($event, 5)" name="iban4" ng-paste="pasteIban($event)" ng-model="affiliatesSettingsCtrl.person.personal_info.iban4">
              <input class="form-control iban-input" id="iban_5" type="text" maxlength="4" ng-readonly="!affiliatesSettingsCtrl.editMode" name="iban5" ng-paste="pasteIban($event)" ng-model="affiliatesSettingsCtrl.person.personal_info.iban5">
              <input class="form-control iban-input" style="clear:both; width: 100%;"  type="hidden" name="iban" ng-model="affiliatesSettingsCtrl.person.personal_info.iban">
              <div class="col-xs-12 no-pad error-info" ng-if="affiliatesSettingsCtrl.saved&&!affiliatesSettingsCtrl.dataForm.$dirty">
                <span translate="settings.general.CHANGES_SAVED"><i class="ion-checkmark text-red"></i></span>
              </div>
              <div class="col-xs-12 no-pad error-info" ng-if="affiliatesSettingsCtrl.showErrors">
                <span translate="settings.general.INCORRECT_IBAN"></span>
              </div>
              <div class="col-md-12 no-pad block-separator-light"></div>
              <div class="col-md-12 mt-20 text-center">
                <button class="btn btn-red btn-small auto-center" ng-click="affiliatesSettingsCtrl.update()" ng-if="affiliatesSettingsCtrl.editMode" translate="settings.SAVE_IBAN"></button>
                <?php //if (!array_key_exists("iban", $person['personal_info']) || empty($person['personal_info']['iban'])): ?>
                  <button class="btn btn-small btn-black-on-white" ng-click="affiliatesSettingsCtrl.changeModeFn()" ng-if="!affiliatesSettingsCtrl.editMode && !affiliatesSettingsCtrl.success" translate="settings.ADD_IBAN"></button>
                  <button class="btn btn-small btn-black-on-white" ng-click="affiliatesSettingsCtrl.changeModeFn()" ng-if="!affiliatesSettingsCtrl.editMode && affiliatesSettingsCtrl.success" translate="settings.EDIT_IBAN"></button>
                <?php //else: ?>
                  <button class="btn btn-small btn-black-on-white" ng-click="affiliatesSettingsCtrl.changeModeFn()" ng-if="!affiliatesSettingsCtrl.editMode" translate="settings.EDIT_IBAN"></button>
                <?php //endif;?>
              </div>
            </form>
          <?php //} ?> -->

        <?php } ?>

        <?php if($person->isClient() /*&& isset($person->available_earnings) && $person->available_earnings > 0 */)  { ?>
          <div class="line-title-only col-md-12 no-pad"><span translate="settings.general.HOW_USE_TODEVISE_CREDIT"></span></div>
          <div class="col-md-12 no-pad block-separator-light"></div>

          <p translate="settings.general.HOW_USE_TODEVISE_CREDIT_1"> </p>
          <div class="col-md-12 no-pad block-separator-light"></div>
          <p translate="settings.general.HOW_USE_TODEVISE_CREDIT_2"> </p>
          <div class="col-md-12 no-pad block-separator-light"></div>
          <p translate="settings.general.HOW_USE_TODEVISE_CREDIT_3"> </p>

        <?php } ?>
			</div>
		</div>

		<div class="col-md-12 no-pad block-separator-full"></div>

    <div class="col-md-12 no-pad">
      <div class="col-xs-12 no-pad">
				<div class="line-title-only col-md-12 no-pad"><span translate="settings.general.ACTIVITY_HISTORY"></span></div>
          <?php $lines = count($history_lines); ?>
          <?php if ($lines) { ?>
            <?php foreach ($history_lines as $key => $line) { ?>
              <?php if($line['type'] == 'earning') { ?>
                <p class="historic_line">
                  <span class="text-grey date"><?php echo date('Y-m-d', $line['created_at']->sec); ?></span>
                  <span translate="settings.general.YOU_EARNED"></span>
                  <span><b><?php echo $line['amount']; ?>€</b></span>
                    <span translate="settings.general.FROM_PURCHASE"></span>
                  <span><a class="historic_link" href="<?php echo $line['person']->getMainLink(); ?>" target="_blank"><?php echo $line['person']->getName(); ?></a></span>
                </p>
              <?php } else if ($line['type'] == 'code_used') { ?>
                <p class="historic_line">
                  <span class="text-grey date"><?php echo date('Y-m-d', $line['created_at']->sec); ?></span>
                  <span><a class="historic_link" href="<?php echo $line['person']->getMainLink(); ?>" target="_blank"><?php echo $line['person']->getName(); ?></a></span>
                  <span translate="settings.general.HISTORY_CODE_USED"></span>
                </p>
              <?php } ?>
            <?php } ?>
          <?php } else { ?>
                  <span translate="settings.general.NO_ACTIVITY_HISTORY"></span>
          <?php } ?>
      </div>
    </div>
	</div>
</div>
