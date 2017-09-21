<?php

use app\assets\desktop\deviser\GlobalAsset;
use app\models\Person;
use yii\helpers\Json;

GlobalAsset::register($this);

/** @var Person $person */
/** @var \app\models\Box $box */
/** @var \app\models\Box[] $moreBoxes */

$this->title = Yii::t('app/public',
	'BOX_NAME_BY_PERSON_NAME',
	['box_name' => $box->name, 'person_name' => $person->getName()]
);

$this->params['person'] = $person;
$this->params['person_menu_active_option'] = 'boxes';
$this->params['person_links_target'] = 'public_view';

$this->registerJs("var box = ".Json::encode($box), yii\web\View::POS_HEAD, 'box-script');

?>

<div class="store" ng-controller="boxDetailCtrl as boxDetailCtrl">
	<div>
		<div class="header-boxes">
			<div class="container">
			<div class="col-md-8 avatar-boxes-wrapper">
				<i class="ion-ios-arrow-left"></i>
				<img class="avatar-header-boxes" src="<?=$person->getProfileImage()?>">
				<?php if ($person->isConnectedUser()) { ?>
					<a href="<?=$person->getBoxesLink()?>">
						<span>My profile</span>
					</a>
				<?php } else  { ?>
					<a href="<?=$person->getBoxesLink()?>">
						<span><?=$person->getName()?></span>
					</a>
				<?php } ?>
			</div>
			<?php if ($person->isPersonEditable()) { ?>
				<div class="col-md-4" style="padding-top: 17px;">
					<button class="btn btn-default pull-right delete-btn" ng-click="boxDetailCtrl.openDeleteBoxModal()"><span translate="person.boxes.DELETE_BOX"></span></button>
					<button class="btn btn-red pull-right edit-btn" ng-click="boxDetailCtrl.openEditBoxModal()"><span translate="person.boxes.EDIT_BOX"></span></button>
				</div>
			<?php } ?>
			</div>
		</div>
		<div class="box-columns-wrapper">
			<div class="container">
			<div class="col-md-3 left-box-column">
				<span class="title" ng-bind="boxDetailCtrl.box.name"></span>
				<p ng-bind="boxDetailCtrl.box.description"></p>
			</div>
			<div class="col-md-9 right-box-column">
				<p class="text-center empty-box-text" ng-if="boxDetailCtrl.box.products.length == 0" ng-cloak><span translate="person.boxes.BOX_IS_EMPTY"></span></p>
				<div class="other-products-wrapper">
					<div id="boxes-container" class="macy-container" data-columns="6">
						<div class="menu-category list-group" ng-if="boxDetailCtrl.box.products.length > 0" ng-cloak ng-repeat="work in boxDetailCtrl.box.products">
							<div class="grid">
								<figure class="effect-zoe">
									<?php if (!$person->isConnectedUser()) { ?>
										<image-hover-buttons product-id="{{work.id}}" is-loved="{{work.isLoved ? 1 : 0}}">
									<?php } else { ?>
										<span class="close-product-icon" ng-click="boxDetailCtrl.deleteProduct(work.id)">
											<i class="ion-trash-a"></i>
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
		<!--<div class="work-description-wrapper">
			<div class="reviews-wrapper">
						<div class="comment-wrapper">
							<div class="col-sm-1">
								<div class="avatar">
									<img class="cover" src="/imgs/avatar-deviser.jpg">
								</div>
							</div>
							<div class="col-sm-10">
								<input type="text" class="form-control comment-input" id="exampleInputEmail1" translate-attr="{placeholder: 'global.ADD_COMMENT'}">
								<div class="rate-product">
									<span><span translate="global.RATE_PRODUCT"></span></span>
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
				</div>-->
	</div>
</div>

