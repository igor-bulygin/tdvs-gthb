<?php
/** @var \app\models\Person $person */

use app\components\assets\PersonAsset;

PersonAsset::register($this);

$isFollowed = $person->isFollowedByConnectedUser() ? 'true' : 'false';
?>



<div ng-controller="personComponentCtrl as personComponentCtrl" ng-init="personComponentCtrl.init(<?=$isFollowed?>)">
	<div class="row ml-15 mr-15">
		<div class="col-xs-12 showcase p-0 pr-5 mw-100">
			<div class="col-sm-4 col-lg-3 p-0">
				<a href="<?= $person->getMainLink()?>">
					<img class="deviser-discover-img showcase-image img-round img-fit ft-left m-5" src="<?= $person->getHeaderSmallImage(150, 150) ?>" width="100" height="100">
				</a>
			</div>
			<div class="col-sm-8 col-lg-9 pr-0 mt-10 pl-20">
				<a href="<?= $person->getMainLink()?>">
					<div class="title-product-name sm align-left pl-xs-5">
						<span><?= $person->getName() ?></span>
					</div>
					<div class="location align-left pl-xs-5 ml-xs-90" style="margin-top: -10px;"><?= $person->personalInfoMapping->getCityLabel() ?></div>
				</a>
				<div class="ft-right mt-10" ng-if="!personComponentCtrl.isConnectedUser('<?=$person->short_id?>')">
					<button class="btn btn-follow btn-icon" ng-click="personComponentCtrl.follow('<?=$person->short_id?>')" ng-cloak ng-if="!personComponentCtrl.isFollowed"><i class="hidden ion-ios-star"></i><span class="p-20"><span translate="discover.FOLLOW"></span></span></button>
					<button class="btn btn-follow btn-black" ng-click="personComponentCtrl.unFollow('<?=$person->short_id?>')" ng-cloak ng-if="personComponentCtrl.isFollowed"><i class="ion-ios-star red-text hidden"></i><span class="p-20"><span translate="discover.UNFOLLOW"></span></span></button>
				</div>
			</div>
		</div>
	</div>
</div>
