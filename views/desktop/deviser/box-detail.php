<?php
use app\assets\desktop\pub\BoxesViewAsset;
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Json;
use yii\helpers\Url;

BoxesViewAsset::register($this);

/** @var Person $person */
/** @var \app\models\Box $box */
/** @var \app\models\Box[] $moreBoxes */

$this->title = 'Box '.$box->name.' by ' . $person->personalInfoMapping->getBrandName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'boxes';
$this->params['person_links_target'] = 'public_view';

/** array $faq */

// <a class="edit-faq-btn" href="<***?= Url::to(["deviser/faq-edit", "slug" => $person->slug, 'deviser_id' => $person->short_id])?****>">+ ADD / EDIT QUESTIONS</a>
$this->registerJs("var box = ".Json::encode($box), yii\web\View::POS_HEAD, 'box-script');

?>

<div class="store" ng-controller="boxDetailCtrl as boxDetailCtrl">
	<div class="container">
        <div class="row" style="background-color: #2e2e2e; height: 73px; opacity: 0.8;">
            <div class="col-md-8" style="padding-top: 10px;">
                <img src="<?=$person->getAvatarImage128()?>" style="max-width: 50px;">
                <?php if ($person->isConnectedUser()) { ?>
                    <a href="<?=Url::to(["deviser/boxes", "slug" => $person->slug, 'deviser_id' => $person->short_id]);?>">
                        <span style="color: white;">&lt; My profile</span>
                    </a>
                <?php } else  { ?>
                    <a href="<?=Url::to(["deviser/boxes", "slug" => $person->slug, 'deviser_id' => $person->short_id]);?>">
                        <span style="color: white;">&lt; <?=$person->getBrandName()?></span>
                    </a>
                <?php } ?>
            </div>
            <?php if ($person->isDeviserEditable()) { ?>
                <div class="col-md-2" style="padding-top: 17px;">
                    <button class="btn btn-default btn-green" ng-click="boxDetailCtrl.openEditBoxModal()">Edit box</button>
                </div>
                <div class="col-md-2" style="padding-top: 17px;">
                    <button class="btn btn-default" ng-click="boxDetailCtrl.openDeleteBoxModal()">Delete box</button>
                </div>
            <?php } ?>
        </div>
		<div class="row">
			<div class="col-md-3" style="background-color: #636363">
				<h3 style="color: white;" ng-bind="boxDetailCtrl.box.name"></h3>
				<p style="color:white;" ng-bind="boxDetailCtrl.box.description"></p>
			</div>
			<div class="col-md-9" style="background-color: black;">
                <p class="text-center" style="font-size: 24px; color: white;" ng-if="boxDetailCtrl.box.products.length == 0" ng-cloak>This box is empty</p>

                <div class="col-md-2 col-sm-4 col-xs-6 pad-grid" ng-if="boxDetailCtrl.box.products.length > 0" ng-cloak ng-repeat="work in boxDetailCtrl.box.products">
                    <div class="grid">
						<figure class="effect-zoe">
							<?php if (!$person->isConnectedUser()) { ?>
								<image-hover-buttons product-id="{{work.id}}" is-loved="{{work.isLoved ? 1 : 0}}">
							<?php } else { ?>
								<span class="close-product-icon" ng-click="boxDetailCtrl.deleteProduct(work.id)">
									<i class="ion-android-close"></i>
								</span>
							<?php } ?>
                                <a ng-href="{{work.link}}">
                                    <img class="grid-image" ng-src="{{work.url_image_preview || '/imgs/product_placeholder.png'}}">
								</a>
							<?php if (!$person->isConnectedUser()) { ?>
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
        <?php /*
        <div class="row">
            <div class="col-lg-12">
                <div class="reviews-wrapper">
                    <div class="comment-wrapper">
                        <div class="col-sm-1">
                            <div class="avatar">
                                <img class="cover" src="/imgs/avatar-deviser.jpg">
                            </div>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" class="form-control comment-input" id="exampleInputEmail1" placeholder="Add your comment">
                        </div>
                        <div class="col-sm-1">
                            <div class="arrow-btn">
                                <i class="ion-android-navigate"></i>
                            </div>
                        </div>
                    </div>
                    <div class="comment-user">
                        <div class="avatar">
                            <img class="cover" src="/imgs/avatar-deviser.jpg">
                        </div>
                        <div class="comment">
                            <div class="name-date">
                                <span class="name">Alice Pierce</span>
                                <span class="date">1 day ago</span>
                            </div>
                            <div class="comment-text">Vivamus ultricies mauris mi, nec imperdiet quam facilisis eget.
                            </div>
                            <div class="replay">
                                <span>Reply</span>
                            </div>
                        </div>
                    </div>
                    <div class="comment-user">
                        <div class="avatar">
                            <img class="cover" src="/imgs/avatar-deviser.jpg">
                        </div>
                        <div class="comment">
                            <div class="name-date">
                                <span class="name">Alice Pierce</span>
                                <span class="date">1 day ago</span>
                            </div>
                            <div class="comment-text">Vivamus ultricies mauris mi, nec imperdiet quam facilisis eget.
                            </div>
                            <div class="replay">
                                <span>Reply</span>
                            </div>
                        </div>
                    </div>
                    <div class="comment-user response">
                        <div class="avatar">
                            <img class="cover" src="/imgs/avatar-deviser.jpg">
                        </div>
                        <div class="comment">
                            <div class="name-date">
                                <span class="name">Alice Pierce</span>
                                <span class="date">1 day ago</span>
                            </div>
                            <div class="comment-text">Vivamus ultricies mauris mi, nec imperdiet quam facilisis eget ultricies mauris mi, nec imperdiet quam facilisis eget ultricies mauris mi, nec imperdiet quam facilisis eget ultricies mauris mi, nec imperdiet quam facilisis eget ultricies mauris mi, nec imperdiet quam facilisis eget ultricies mauris mi, nec imperdiet quam facilisis eget.
                            </div>
                        </div>
                    </div>
                    <div class="comment-user">
                        <div class="avatar">
                            <img class="cover" src="/imgs/avatar-deviser.jpg">
                        </div>
                        <div class="comment">
                            <div class="name-date">
                                <span class="name">Alice Pierce</span>
                                <span class="date">1 day ago</span>
                            </div>
                            <div class="comment-text">Vivamus ultricies mauris mi, nec imperdiet quam facilisis eget.
                            </div>
                            <div class="replay">
                                <span>Reply</span>
                            </div>
                        </div>
                    </div>
                    <div class="load-wrapper">
                        <i class="ion-ios-arrow-down"></i>
                        <span class="green">LOAD MORE</span>
                        <span class="more">24 comments more</span>
                    </div>
                </div>
            </div>
        </div>
        */ ?>
        <div class="row">
            <p class="text-center">More boxes</p>
            <br />
        <?php foreach ($moreBoxes as $oneBox) {
            $products = $oneBox->getProducts();
            if (empty($products) || $oneBox->short_id == $box->short_id) {
                continue;
            } ?>
            <div class="col-lg-3">

				<?php
				$sizes = [
					1 => [
						[251, 373],
					],
					2 => [
						[251, 116],
						[251, 257],
					],
					3 => [
						[124, 116],
						[124, 116],
						[251, 257],
					],
				];
				if (count($products) >= 3) {
					$size = $sizes[3];
				} elseif (count($products) == 2) {
					$size = $sizes[2];
				} else {
					$size = $sizes[1];
				}
				$count = 0;
				foreach ($products as $product) {
					if ($product->product_state != \app\models\Product2::PRODUCT_STATE_ACTIVE || $count > 3) {
						continue;
					}
					$count++;
					if ($count == 1) { ?>
                        <a href="<?= $product->getViewLink()?>">
                            <img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize($size[0][0], $size[0][1]) ?>">
                        </a>
					<?php } elseif ($count == 2) { ?>
                        <a href="<?= $product->getViewLink()?>">
                            <img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize($size[1][0], $size[1][1]) ?>">
                        </a>
					<?php } elseif ($count == 3) { ?>
                        <a href="<?= $product->getViewLink()?>">
                            <img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize($size[2][0], $size[2][1]) ?>">
                        </a>
					<?php } ?>
				<?php } ?>
                <a href="<?= $oneBox->getViewLink() ?>"><?=$oneBox->name?> (<?=count($products)?>)</a>
            </div>
        <?php } ?>
        </div>
	</div>
</div>

