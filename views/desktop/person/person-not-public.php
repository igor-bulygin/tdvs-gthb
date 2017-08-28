<?php

use app\assets\desktop\deviser\GlobalAsset;
use app\components\PersonHeader;
use app\helpers\Utils;
use app\models\Person;

GlobalAsset::register($this);

/** @var Person $person */
/** @var \app\models\Product[] $products */
$this->title = Yii::t('app/public',
	'Almost done! {person_name} - Todevise',
	['person_name' => $person->getName()]
);
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
						<p><span translate="FILL_PROFILE"></span></p>
						<img class="image-loved" src="/imgs/loved-image.png">
					</div>
				<?php } ?>
				<?php if ($person->isDeviser()) { ?>
					<p class="success-black-subtitle text-center"><span translate="BEFORE_PUBLIC"></span></p>
				<?php } ?>
			</div>
		</div>
		<?php if ($person->isDeviser()) { ?>
			<div class="row text-center">
				<a class="big-btn btn btn-default <?=$person->hasShippingSettings() ? 'done' : ''?>" href="<?=$person->getSettingsLink('shipping')?>">
					<span translate="ADD_SHIPPING"></span>
					<?php if ($person->hasShippingSettings()) { ?><i class="red-check ion-checkmark"></i> <?php } ?>
				</a>
			</div>
			<div class="row text-center">
				<a class="big-btn btn btn-default <?=$person->hasStripeInfo() ? 'done' : ''?>" href="<?=$person->getSettingsLink('connect-stripe')?>">
					<span translate="ADD_BANK_ACCOUNT"></span>
					<p class="big-btn-message"><span translate="WILL_REDIRECTED"></span></p>
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
									<i class="remove-product ion-close-circled" title="Delete work" ng-click="personNotPublicCtrl.open_modal_delete('<?= $product->short_id ?>')"></i>
								
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
			<button class="regular-btn btn btn-default <?=!$person->canPublishProfile() ? ' disabled ' : ''?>" ng-click="personNotPublicCtrl.makeProfilePublic()" <?=!$person->canPublishProfile() ? ' disabled ' : ''?>>Make profile public</button>
		</div>
	</div>
	<script type="text/ng-template" id="modalDeleteProduct.html">
		<div class="modal-delete">
			<div class="modal-header">
				<h3 class="modal-title"></h3>
			</div>
			<div class="modal-body">
				<p><span translate="DELETE_WORK"></span></p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default btn-green pull-left" ng-click="modalDeleteProductCtrl.close()"><span translate="CANCEL"></span></button>
				<button class="btn btn-default pull-right" ng-click="modalDeleteProductCtrl.ok()"><span translate="DELETE"></span></button>
			</div>
		</div>
	</script>
</div>
