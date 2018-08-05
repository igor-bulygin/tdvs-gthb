<?php
/** @var \app\models\Person $person */

use app\components\assets\PersonAsset;

PersonAsset::register($this);

$isFollowed = $person->isFollowedByConnectedUser() ? 'true' : 'false';
?>



<div ng-controller="personComponentCtrl as personComponentCtrl" ng-init="personComponentCtrl.init(<?=$isFollowed?>)">
	<figure class="showcase influencers">
		<a href="<?= $person->getMainLink()?>">
			<img class="deviser-discover-img showcase-image" src="<?= $person->getHeaderSmallImage(350, 225) ?>">
		</a>
		<figcaption>
			<div class="row no-margin-xs">
				<div class="col-xs-6">
					<a href="<?= $person->getMainLink()?>">
						<div class="title-product-name sm align-left">
							<span><?= $person->getName() ?></span>
						</div>
						<div class="location align-left"><?= $person->personalInfoMapping->getCityLabel() ?></div>
					</a>
				</div>
				<div class="col-xs-6" ng-if="!personComponentCtrl.isConnectedUser('<?=$person->short_id?>')">
					<button  class="btn btn-follow full-size-btn btn-icon" ng-click="personComponentCtrl.follow('<?=$person->short_id?>')" ng-cloak ng-if="!personComponentCtrl.isFollowed"><i class="hidden ion-ios-star"></i><span><span translate="discover.FOLLOW"></span></span></button>
					<button class="btn btn-follow full-size-btn btn-black" ng-click="personComponentCtrl.unFollow('<?=$person->short_id?>')" ng-cloak ng-if="personComponentCtrl.isFollowed"><i class="ion-ios-star red-text hidden"></i><span><span translate="discover.UNFOLLOW"></span></span></button>
				</div>
			</div>
		</figcaption>
	</figure>
</div>
