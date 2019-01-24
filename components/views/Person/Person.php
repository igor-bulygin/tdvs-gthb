<?php
/** @var \app\models\Person $person */

use app\components\assets\PersonAsset;

PersonAsset::register($this);

$isFollowed = $person->isFollowedByConnectedUser() ? 'true' : 'false';
?>



<div ng-controller="personComponentCtrl as personComponentCtrl" ng-init="personComponentCtrl.init(<?=$isFollowed?>)">
	<div class="showcase">
			<div style="width: 100px; float: left; margin: 5px; padding: 0;">
				<a href="<?= $person->getMainLink()?>">
					<img class="deviser-discover-img showcase-image img-round img-fit ft-left m-5" src="<?= $person->getHeaderSmallImage(150, 150) ?>" width="100" height="100">
				</a>
			</div>
			<div style="width: calc(100% - 120px ); float: left; margin: 15px 5px 5px 5px; padding: 5px;">
				<a href="<?= $person->getMainLink()?>">
					<div class="title-product-name sm align-left" style="height:26px;">
						<span><?= $person->getName() ?></span>
					</div>
					<div class="align-left"><?= $person->personalInfoMapping->getCityLabel() ?></div>
				</a>
				<div class="ft-right mt-10" ng-if="!personComponentCtrl.isConnectedUser('<?=$person->short_id?>')">
					<button class="btn btn-follow btn-icon" ng-click="personComponentCtrl.follow('<?=$person->short_id?>')" ng-cloak ng-if="!personComponentCtrl.isFollowed"><i class="hidden ion-ios-star"></i><span class="p-20"><span translate="discover.FOLLOW"></span></span></button>
					<button class="btn btn-follow btn-black" ng-click="personComponentCtrl.unFollow('<?=$person->short_id?>')" ng-cloak ng-if="personComponentCtrl.isFollowed"><i class="ion-ios-star red-text hidden"></i><span class="p-20"><span translate="discover.UNFOLLOW"></span></span></button>
				</div>
			</div>
	</div>
</div>
