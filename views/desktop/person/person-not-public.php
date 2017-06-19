<?php
use app\assets\desktop\deviser\PersonNotPublicAsset;
use app\components\PersonHeader;
use app\helpers\Utils;
use app\models\Person;

PersonNotPublicAsset::register($this);

/** @var Person $person */
/** @var \app\models\Product[] $products */

$this->title = 'Almost done! ' . $person->getName() . ' - Todevise';
$this->params['person'] = $person;

$this->registerJs("var person = ".\yii\helpers\Json::encode($person), yii\web\View::POS_HEAD, 'person-not-public-var-script');

?>

<?= PersonHeader::widget() ?>

<div class="store" ng-controller="personNotPublicCtrl as personNotPublicCtrl">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<img class="icon-face" src="/imgs/happy-face.svg">
				<h3 class="succes-black-title text-center">Almost done!</h3>
				<?php if ($person->isInfluencer()) {?>
					<div class="text-center">
						<p>If you prefer, before making your profile public you can fill it with products you like.</p>
						<img class="image-loved" src="/imgs/loved-image.png">
					</div>
				<?php } ?>
				<?php if ($person->isDeviser()) { ?>
					<p class="success-black-subtitle text-center">Before making your profile public, please complete the following steps:</p>
				<?php } ?>
			</div>
		</div>
		<?php if ($person->isDeviser()) { ?>
			<div class="row text-center">
				<a class="big-btn btn btn-default <?=$person->hasShippingSettings() ? 'done' : ''?>" href="<?=$person->getSettingsLink('shipping')?>">
					Add shipping prices
					<?php if ($person->hasShippingSettings()) { ?><i class="red-check ion-checkmark"></i> <?php } ?>
				</a>
			</div>
			<div class="row text-center">
				<a class="big-btn btn btn-default <?=$person->hasStripeInfo() ? 'done' : ''?>" href="<?=$person->getSettingsLink('connect-stripe')?>">
					Add a bank account
					<p class="big-btn-message">You will be redirected to an external website</p>
					<?php if ($person->hasStripeInfo()) { ?><i class="red-check ion-checkmark"></i> <?php } ?>
				</a>
				
			</div>
			<div class="row text-center">
				<a class="big-btn btn btn-default <?=$person->hasPublishedProducts() ? 'done' : ''?>" href="<?=$person->getCreateWorkLink()?>">
					Add a product
					<?php if ($person->hasPublishedProducts()) { ?><i class="red-check ion-checkmark"></i> <?php } ?>
				</a>
			</div>
			<div class="products-added-wrapper mt-30">
				<div class="row">
					<?php foreach ($products as $i => $product) { ?>
						<div class="col-lg-15">
							<div class="product-added-item">
							<?php if ($person->isPersonEditable()) { ?>
								
									<a class="edit-product" href="<?= $product->getEditLink()?>" title="Edit work">
										<i class="ion-edit"></i>
									</a>
									<i class="remove-product ion-close-circled"></i>
								
							<?php } ?>
							<a href="<?= $product->getViewLink() ?>">
								<img class="product-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize(112, 112) ?>">
							</a>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>

		<?php } ?>
		<div class="row mb-100 text-center">
			<button class="regular-btn btn btn-default disabled" ng-click="personNotPublicCtrl.makeProfilePublic()" <?=!$person->canPublishProfile() ? ' disabled ' : ''?>>Make profile public</button>
		</div>
	</div>
</div>
