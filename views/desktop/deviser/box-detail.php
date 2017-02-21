<?php
use app\assets\desktop\pub\BoxesViewAsset;
use app\models\Person;
use yii\helpers\Json;
use yii\helpers\Url;

BoxesViewAsset::register($this);

/** @var Person $deviser */
/** @var \app\models\Box $box */

$this->title = 'Box '.$box->name.' by ' . $deviser->personalInfoMapping->getBrandName() . ' - Todevise';
$this->params['deviser'] = $deviser;
$this->params['deviser_menu_active_option'] = 'boxes';
$this->params['deviser_links_target'] = 'public_view';

/** array $faq */

// <a class="edit-faq-btn" href="<***?= Url::to(["deviser/faq-edit", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?****>">+ ADD / EDIT QUESTIONS</a>
$this->registerJs("var box = ".Json::encode($box), yii\web\View::POS_HEAD, 'box-script');

?>

<div class="store" ng-controller="boxDetailCtrl as boxDetailCtrl">
	<div class="container">
		<div class="row">
			<div class="col-md-12" style="background-color: #2e2e2e; height: 73px; opacity: 0.8;">
				<div class="col-md-8" style="padding-top: 10px;">
					<img src="<?=$deviser->getAvatarImage128()?>" style="max-width: 50px;">
					<?php if ($deviser->isConnectedUser()) { ?>
						<a href="<?=Url::to(["deviser/boxes", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id]);?>">
							<span style="color: white;">&lt; My profile</span>
						</a>
					<?php } else  { ?>
						<a href="<?=Url::to(["deviser/boxes", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id]);?>">
							<span style="color: white;">&lt; <?=$deviser->getBrandName()?></span>
						</a>
					<?php } ?>
				</div>
				<?php if ($deviser->isDeviserEditable()) { ?>
					<div class="col-md-2" style="padding-top: 17px;">
						<button class="btn btn-default btn-green" ng-click="boxDetailCtrl.openEditBoxModal()">Edit box</button>
					</div>
					<div class="col-md-2" style="padding-top: 17px;">
						<button class="btn btn-default" ng-click="boxDetailCtrl.openDeleteBoxModal()">Delete box</button>
					</div>
				<?php } ?>
			</div>
			<div class="col-md-3" style="background-color: #636363">
				<h3 style="color: white;" ng-bind="boxDetailCtrl.box.name"></h3>
				<p style="color:white;" ng-bind="boxDetailCtrl.box.description"></p>
			</div>
			<div class="col-md-9" style="background-color: black;">
                <p class="text-center" style="font-size: 24px; color: white;" ng-if="boxDetailCtrl.box.products.length == 0">This box is empty</p>

                <div class="col-md-2 col-sm-4 col-xs-6 pad-grid" ng-if="boxDetailCtrl.box.products.length > 0" ng-repeat="work in boxDetailCtrl.box.products">
                    <div class="grid">
						<figure class="effect-zoe">
							<?php if (!$deviser->isConnectedUser()) { ?>
								<image-hover-buttons product-id="{{work.id}}" is-loved="{{work.isLoved ? 1 : 0}}">
							<?php } else { ?>
								<span class="close-product-icon" ng-click="boxDetailCtrl.deleteProduct(work.id)">
									<i class="ion-android-close"></i>
								</span>
							<?php } ?>
                                <a ng-href="{{work.link}}">
                                    <img class="grid-image" ng-src="{{work.url_image_preview || '/imgs/product_placeholder.png'}}">
								</a>
							<?php if (!$deviser->isConnectedUser()) { ?>
								</image-hover-buttons>
							<?php } ?>
                            <figcaption>
                                <a ng-href="{{work.link}}">
                                    <p class="instauser">
                                        <span ng-bind="work.name"></span>
                                    </p>
                                </a>
                            </figcaption>
						</figure>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

