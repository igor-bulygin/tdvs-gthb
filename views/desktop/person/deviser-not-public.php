<?php
use app\components\PersonHeader;
use app\models\Person;
use app\assets\desktop\deviser\DeviserNotPublicAsset;

DeviserNotPublicAsset::register($this);

/** @var Person $person */

$this->title = 'Almost done! ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;

$this->registerJs("var person = ".\yii\helpers\Json::encode($person), yii\web\View::POS_HEAD, 'deviser-not-public-var-script');

?>

<?= PersonHeader::widget() ?>

<div class="store" ng-controller="deviserNotPublicCtrl as deviserNotPublicCtrl">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h3 class="text-center">Almost done!</h3>
				<p class="text-center">Before making your profile public, please complete the following steps:</p>
			</div>
		</div>
		<div class="row text-center">
				<?php if (!$person->hasShippingSettings()) { ?>
					<a class="btn btn-default" href="<?=$person->getSettingsLink('shipping')?>">Add shipping prices</a>
				<?php } else { ?>
					<a class="btn btn-default" href="<?=$person->getSettingsLink('shipping')?>">Add shipping prices (DONE)</a>
				<?php } ?>
		</div>
		<div class="row text-center">
				<?php if (!$person->hasStripeInfo()) { ?>
					<a class="btn btn-default" href="<?=$person->getSettingsLink('connect-stripe')?>">Add a bank account</a>
				<?php } else { ?>
					<a class="btn btn-default" href="<?=$person->getSettingsLink('connect-stripe')?>">Add a bank account (DONE)</a>
				<?php } ?>
		</div>
		<div class="row text-center">
				<?php if (!$person->hasPublishedProducts()) { ?>
					<a class="btn btn-default" href="<?=$person->getCreateWorkLink()?>">Add a product</a>
				<?php } else { ?>
					<a class="btn btn-default" href="<?=$person->getCreateWorkLink()?>">Add a product (DONE)</a>
				<?php } ?>
		</div>
		<div class="row text-center">
				<?php if ($person->canPublishProfile()) { ?>
					<button class="btn btn-default" ng-click="deviserNotPublicCtrl.makeProfilePublic()">Make profile public</button>
				<?php } else { ?>
					<button class="btn btn-default" disabled>Make profile public</button>
				<?php } ?>
		</div>
	</div>
</div>
