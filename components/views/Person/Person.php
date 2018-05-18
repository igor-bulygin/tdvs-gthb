<?php
/** @var \app\models\Person $person */
use app\components\assets\PersonAsset;

PersonAsset::register($this);
?>



<div ng-controller="personComponentCtrl as personComponentCtrl">
	<figure class="showcase influencers">
		<img class="deviser-discover-img showcase-image" src="<?= $person->getHeaderSmallImage() ?>">
		<figcaption>
			<div class="row">
				<div class="col-md-6">
					<div class="title-product-name sm align-left">
						<span><?= $person->getName() ?></span>
					</div>
					<div class="location align-left"><?= $person->personalInfoMapping->getCityLabel() ?></div>
				</div>
				<div class="col-md-6">
					<?php if ($person->isFollowedByConnectedUser()) { ?>
						<button  class="btn btn-icon mt-5" ng-click="personComponentCtrl.unFollow(' <?php $person ?> ')"><i class="ion-ios-star"></i><span>Unfollow</span></button>
					<?php } else { ?>
						<button class="btn btn-icon mt-5" ng-click="personComponentCtrl.follow(' <?php $person ?> ')"><i class="ion-ios-star-outline"></i><span>Follow</span></button>
					<?php } ?>
				</div>
			</div>
		</figcaption>
	</figure>
</div>