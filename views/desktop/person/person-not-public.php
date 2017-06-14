<?php
use app\components\PersonHeader;
use app\models\Person;
use app\assets\desktop\deviser\PersonNotPublicAsset;

PersonNotPublicAsset::register($this);

/** @var Person $person */

$this->title = 'Almost done! ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;

$this->registerJs("var person = ".\yii\helpers\Json::encode($person), yii\web\View::POS_HEAD, 'person-not-public-var-script');

?>

<?= PersonHeader::widget() ?>

<div class="store" ng-controller="personNotPublicCtrl as personNotPublicCtrl">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h3 class="text-center">Almost done!</h3>
				<?php if ($person->isInfluencer()) {?>
					<div class="text-center">
						<p>If you prefer, before making your profile public you can fill it with products you like.</p>
						<img class="image-loved" src="/imgs/loved-image.png">
					</div>
				<?php } ?>
				<?php if ($person->isDeviser()) { ?>
					<p class="text-center">Before making your profile public, please complete the following steps:</p>
				<?php } ?>
			</div>
		</div>
		<?php if ($person->isDeviser()) { ?>
			<div class="row text-center">
				<a class="btn btn-default <?=$person->hasShippingSettings() ? 'done' : ''?>" href="<?=$person->getSettingsLink('shipping')?>">Add shipping prices</a>
			</div>
			<div class="row text-center">
				<a class="btn btn-default <?=$person->hasStripeInfo() ? 'done' : ''?>" href="<?=$person->getSettingsLink('connect-stripe')?>">Add a bank account</a>
			</div>
			<div class="row text-center">
				<a class="btn btn-default <?=$person->hasPublishedProducts() ? 'done' : ''?>" href="<?=$person->getCreateWorkLink()?>">Add a product</a>
			</div>
		<?php } ?>
		<div class="row mb-100 text-center">
			<button class="btn btn-default" ng-click="personNotPublicCtrl.makeProfilePublic()" <?=!$person->canPublishProfile() ? ' disabled ' : ''?>>Make profile public</button>
		</div>
	</div>
</div>
