<?php
use app\assets\desktop\pub\BoxesViewAsset;
use app\helpers\Utils;
use app\models\Person;
use yii\helpers\Json;

BoxesViewAsset::register($this);

/** @var Person $person */
/** @var \app\models\Box $box */
/** @var \app\models\Box[] $moreBoxes */

$this->title = 'Box '.$box->name.' by ' . $person->personalInfoMapping->getBrandName() . ' - Todevise';
$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'boxes';
$this->params['person_links_target'] = 'public_view';

$this->registerJs("var box = ".Json::encode($box), yii\web\View::POS_HEAD, 'box-script');

?>

<div class="store" ng-controller="boxDetailCtrl as boxDetailCtrl">
	<div class="container">
		<div class="row header-boxes">
			<div class="col-md-8 avatar-boxes-wrapper">
				<i class="ion-ios-arrow-left"></i>
				<img class="avatar-header-boxes" src="<?=$person->getAvatarImage128()?>">
				<?php if ($person->isConnectedUser()) { ?>
					<a href="<?=$person->getBoxesLink()?>">
						<span>My profile</span>
					</a>
				<?php } else  { ?>
					<a href="<?=$person->getBoxesLink()?>">
						<span><?=$person->getBrandName()?></span>
					</a>
				<?php } ?>
			</div>
			<?php if ($person->isPersonEditable()) { ?>
				<div class="col-md-4" style="padding-top: 17px;">
					<button class="btn btn-default pull-right delete-btn" ng-click="boxDetailCtrl.openDeleteBoxModal()">Delete box</button>
					<button class="btn btn-green pull-right edit-btn" ng-click="boxDetailCtrl.openEditBoxModal()">Edit box</button>
				</div>
			<?php } ?>
		</div>
		<div class="row box-columns-wrapper">
			<div class="col-md-3 left-box-column">
				<span class="title" ng-bind="boxDetailCtrl.box.name"></span>
				<p ng-bind="boxDetailCtrl.box.description"></p>
			</div>
			<div class="col-md-9 right-box-column">
				<p class="text-center empty-box-text" ng-if="boxDetailCtrl.box.products.length == 0" ng-cloak>This box is empty</p>

				<div class="col-md-3 col-xs-6 pad-grid" ng-if="boxDetailCtrl.box.products.length > 0" ng-cloak ng-repeat="work in boxDetailCtrl.box.products">
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
										<img class="grid-image" ng-src="{{work.main_photo || '/imgs/product_placeholder.png'}}">
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
		<div class="work-description-wrapper">
			<div class="reviews-wrapper">
						<div class="comment-wrapper">
							<div class="col-sm-1">
								<div class="avatar">
									<img class="cover" src="/imgs/avatar-deviser.jpg">
								</div>
							</div>
							<div class="col-sm-10">
								<input type="text" class="form-control comment-input" id="exampleInputEmail1" placeholder="Add your comment">
								<div class="rate-product">
									<span>Rate this product</span>
									<span class="score">
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
										</span>
								</div>
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
									<span class="score">
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
										</span>
									<span class="useful">300  member found this comment useful</span>
								</div>
							</div>
							<div class="helpful">
								<span>Is this review helpful to you ?</span>
								<div class="rounded-btn">Yes</div>
								<div class="rounded-btn">No</div>
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
									<span class="score">
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
										</span>
									<span class="useful">300  member found this comment useful</span>
								</div>
							</div>
							<div class="helpful">
								<span>Is this review helpful to you ?</span>
								<div class="rounded-btn">Yes</div>
								<div class="rounded-btn">No</div>
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
							<div class="helpful">
								<span>Is this review helpful to you ?</span>
								<div class="rounded-btn">Yes</div>
								<div class="rounded-btn">No</div>
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
									<span class="score">
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
										</span>
									<span class="useful">300  member found this comment useful</span>
								</div>
							</div>
							<div class="helpful">
								<span>Is this review helpful to you ?</span>
								<div class="rounded-btn">Yes</div>
								<div class="rounded-btn">No</div>
							</div>
						</div>
						<div class="load-wrapper">
							<i class="ion-ios-arrow-down"></i>
							<span>LOAD MORE</span>
							<span class="more">24 comments more</span>
						</div>
					</div>
				</div>
		<div class="row more-boxes-wrapper">
			<p class="text-center more-boxes-text">More boxes</p>
			<br />
		<?php foreach ($moreBoxes as $oneBox) {
			$products = $oneBox->getProducts();
			if (empty($products) || $oneBox->short_id == $box->short_id) {
				continue;
			} ?>
			<div class="col-lg-3">
				
				<div class="boxes-wrapper">
			
				<?php
				$sizes = [
					1 => [
						[262, 373],
					],
					2 => [
						[262, 116],
						[262, 257],
					],
					3 => [
						[130, 116],
						[129, 116],
						[262, 257],
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
				<a class="group-box-title" href="<?= $oneBox->getViewLink() ?>">
					<span><?=$oneBox->name?> (<?=count($products)?>)</span>
				</a>
				</div>
			</div>
		<?php } ?>
		</div>
	</div>
</div>

