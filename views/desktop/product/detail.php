<?php

use app\assets\desktop\product\GlobalAsset;
use app\assets\desktop\pub\Product2Asset;
use app\helpers\Utils;
use app\models\Country;
use app\models\Person;
use app\models\PersonVideo;

GlobalAsset::register($this);

/** @var Person $person */
/** @var \app\models\Product $product */
/** @var PersonVideo $video */

$this->title = Yii::t('app/public',
	'PRODUCT_BY_PERSON_NAME',
	['product_name' => $product->getName(), 'person_name' => $person->getName()]
);
$productImages = $product->getUrlGalleryImages();
$videos = $product->getVideos();

?>

	<!-- PRODUCT CARD -->
	
	<div class="product" ng-controller="detailProductCtrl as detailProductCtrl">
		<!-- Modal -->
		<div class="modal fade" id="chartModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel"><span translate="product.detail.CHART_SIZES"></span></h4>
					</div>
					<div class="modal-body">
						<table class="table table-striped">
							<thead>
								<tr>
									<td><span ng-bind="detailProductCtrl.product.sizechart.country"></span></td>
									<td ng-repeat="column in detailProductCtrl.product.sizechart.columns"><span ng-bind="column['en-US']"></span></td>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="value in detailProductCtrl.product.sizechart.values">
									<td ng-repeat="item in value track by $index"><span ng-bind="item"></span></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div>
			<div class="container">
				<div class="col-md-8 pad-product">
					<div class="product-photos-wrapper">
						<!-- CAROUSEL-->
						<div id='carousel-custom' class='carousel slide' data-ride='carousel'>
							<!-- Indicators -->
							<div class="row">
								<div class="col-sm-12">
									<div class='carousel-outer'>
										<!-- Wrapper for slides -->
										<div class='carousel-inner'>
											<?php foreach ($productImages as $key => $imageUrl) { ?>
												<div class='item <?= ($key==0) ? ' active ' : ' ' ?>'>
													<img class="product-slide" src='<?= Utils::url_scheme() ?><?= Utils::thumborize($imageUrl)->resize(410, 0) ?>' alt='' />
												</div>
												<?php } ?>
										</div>
										<?php if (count($productImages)>1) { ?>
										<!-- Controls -->
										<a class='left carousel-control' href='#carousel-custom' data-slide='prev'>
											<span class='ion-ios-arrow-left arrow'>
													</span>
										</a>
										<a class='right carousel-control' href='#carousel-custom' data-slide='next'>
											<span class='ion-ios-arrow-right arrow'>
													</span>
										</a>
										<?php } ?>
									</div>
								</div>
								<div class="col-sm-12">
									<ol class='carousel-indicators thumbs mCustomScrollbar'>
										<?php foreach ($productImages as $key => $imageUrl) { ?>
											<li class="col-sm-1" data-target='#carousel-custom' data-slide-to='<?= $key ?>' class='active'>
												<img src='<?= Utils::url_scheme() ?><?= Utils::thumborize($imageUrl)->resize(410, 0) ?>' alt='' />
											</li>
											<?php } ?>
									</ol>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 pad-product">
					<div class="product-data-wrapper">
						<div class="product-data">
							<span class="title" ng-bind="detailProductCtrl.product.name"></span>
							<span class="score">
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
							</span>
							<span class="number-score">(20)</span>
						</div>
						<div class="product-data no-border">
							<div class="price-stock pull-left">
								<div class="stock"><span ng-bind="detailProductCtrl.stock"></span><span translate="product.detail.IN_STOCK"></span></div>
								<div class="product-price">€ <span ng-bind="detailProductCtrl.price"></span></div>
							</div>
							<div class="quantity-wrapper pull-right">
								<button class="btn btn-none btn-summatory" ng-click="detailProductCtrl.changeQuantity(-1)">
									<span>-</span>
								</button>
								<div class="number" ng-bind="detailProductCtrl.quantity"></div>
								<button class="btn btn-none btn-summatory" ng-click="detailProductCtrl.changeQuantity(1)">
									<span>+</span>
								</button>
							</div>
						</div>
						<div class="product-data">
							<ul class="nav nav-tabs product-detail-tabs" role="tablist" ng-if="detailProductCtrl.original_artwork" ng-cloak>
								<li role="presentation" class="no-b-r">
									<a href="#" aria-controls="description" role="tab" data-toggle="tab" ng-click="detailProductCtrl.changeOriginalArtwork(true)"><span translate="product.detail.ORIGINAL"></span></a>
								</li>
								<li role="presentation" class="active">
									<a href="#" aria-controls="works" role="tab" data-toggle="tab" ng-click="detailProductCtrl.changeOriginalArtwork(false)"><span translate="product.detail.PRINTS"></span></a>
								</li>
							</ul>
							<div>
								<form name="detailProductCtrl.tagsForm">
									<div class="form-horizontal">
										<div class="form-group">
											<div class="row-size expand" ng-repeat="option in detailProductCtrl.product.options | orderBy:[detailProductCtrl.selectComparator]">
												<label class="col-sm-3 control-label product-label"><span class="atr" ng-bind="option.name"></span></label>
												<div class="col-sm-9" ng-if="option.values.length > 1 && option.change_reference" ng-cloak>
													<div class="row">
														<div class="col-sm-8">
															<ol name="{{option.id}}" class="nya-bs-select btn-group bootstrap-select form-control product-select ng-class:{'error-input': detailProductCtrl.has_error(detailProductCtrl.tagsForm, detailProductCtrl.tagsForm[option.id])}" ng-model="detailProductCtrl.option_selected[option.id]" ng-change="detailProductCtrl.optionsChanged(option.id, detailProductCtrl.option_selected[option.id])" required>
																<li nya-bs-option="value in option.values" data-value="value.value" ng-class="{'disabled': value.disabled}">
																	<a href="">
																		<span ng-bind="value.text"></span>
																	</a>
																</li>
															</ol>
														</div>
														<div class="col-sm-4 no-pad">
															<a class="view-chart-size" href="#" href="#" data-toggle="modal" data-target="#chartModal" ng-if="option.id==='size'"><span translate="product.detail.VIEW_SIZE_CHART"></span></a>
														</div>
													</div>
												</div>
												<div class="col-sm-9" ng-if="option.values.length === 1 || !option.change_reference">
													<div class="atribute-selected" ng-if="option.values.length === 1">
														<span ng-if="option.values[0].text.length > 1 && !detailProductCtrl.isString(option.values[0].text)" ng-repeat="text in option.values[0].text track by $index">
															<span ng-bind="text"></span><span ng-if="!$last" ng-cloak>,&nbsp;</span>
														</span>
														<span ng-if="detailProductCtrl.isString(option.values[0].text)" ng-bind="option.values[0].text"></span>
													</div>
													<div class="atribute-selected" ng-if="option.values.length > 1" ng-repeat="values in option.values track by $index">
														<span ng-bind="values.text"></span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
							<!--<div class="row-size">
								<form class="form-horizontal" name="detailProductCtrl.selectorForm">
									<div class="form-group" ng-repeat="option in detailProductCtrl.product.options | orderBy:[detailProductCtrl.selectComparator]">
										<tdv-size-selector option="option" options-selected="detailProductCtrl.optionsSelected" get-references="detailProductCtrl.getReferencesFromOptions(options)" ng-if="option.widget_type==='size'"></tdv-size-selector>
										<tdv-color-selector option="option" options-selected="detailProductCtrl.optionsSelected" get-references="detailProductCtrl.getReferencesFromOptions(options)" ng-if="option.widget_type==='color'"></tdv-color-selector>
										<tdv-select-selector option="option" options-selected="detailProductCtrl.optionsSelected" get-references="detailProductCtrl.getReferencesFromOptions(options)" ng-if="option.widget_type==='select'"></tdv-select-selector>
									</div>
								</form>
							</div>-->
							<div class="row-size">
								<button type="button" class="btn btn-medium btn-red auto-center" ng-disabled="detailProductCtrl.stock === 0" ng-click="detailProductCtrl.addToCart(detailProductCtrl.tagsForm)"><i class="ion-android-cart cart-icon-btn"></i> <span translate="product.detail.ADD_TO_CART"></span></button>
							</div>
						</div>
						<!--<div class="product-data">
							<div class="row-size">
								<div class="shipping-policies-wrapper">
									<div class="title">Shipping &amp; Policies</div>
									<div class="policies-row">
										<form class="form-horizontal">
											<div class="form-group">
												<label class="col-sm-4 control-label shipping-label"><span>Shipping price to</span></label>
												<div class="col-sm-5 shipping-sel">
													<select class="form-control selectpicker shipping-select product-select" title="Choose country">
														<option>USA</option>
														<option>SPAIN</option>
													</select>
												</div>
												<div class="col-sm-3">
													is <span class="tax">€4.5</span>
												</div>
											</div>
										</form>
									</div>
									<div class="returns-row">
										Returns: 14 days
									</div>
									<div class="returns-row">
										Warranty:
										
									</div>
								</div>
							</div>
						</div>-->
						<div class="product-data no-border">
							<div class="full-width">
								<?php if ($product->isWorkFromCurrentUser()) { ?>
									<a href="<?=$product->getEditLink()?>" class="btn btn-hart pull-left">
										<i class="ion-edit"></i>
										<span translate="product.detail.EDIT_WORK"></span>
									</a>
								<?php } else { ?>
									<button type="button" class="btn btn-love pull-left" ng-class="detailProductCtrl.product.isLoved ? 'btn-love' : 'btn-love'" ng-click="detailProductCtrl.setLoved()">
										<i class="ion-ios-heart"></i>
									</button>
								<?php } ?>
								<button type="button" class="btn btn-save-box pull-right" ng-click="detailProductCtrl.setBox()">
									<div class="box-icon"></div>
									<span translate="product.detail.SAVE_IN_BOX"></span>
								</button>

							</div>
							<!--<div class="row-size">
								<span class="btn-tagline loved pull-left" translate="product.detail.LOVED"></span>
								<span ng-bind="detailProductCtrl.product.loveds"></span><span translate="product.detail.TIMES"></span>
								<span class="btn-tagline saved pull-right" translate="product.detail.SAVED_IN_X_BOXES" translate-values="{ x:detailProductCtrl.product.boxes}"></span>
							</div>-->
							<div class="full-width row">
								<ul class="social-items">
									<li>
										<span translate="product.detail.SHARE_ON"></span>
									</li>
									<li>
										<a href="#">
											<i class="fa fa-facebook" aria-hidden="true"></i>
										</a>
									</li>
									<li>
										<a class="twitter" href="#">
											<i class="fa fa-twitter" aria-hidden="true"></i>
										</a>
									</li>
									<li>
										<a class="google-plus" href="#">
											<i class="fa fa-google-plus" aria-hidden="true"></i>
										</a>
									</li>
									<li>
										<a href="#">
											<i class="fa fa-pinterest-p" aria-hidden="true"></i>
										</a>
									</li>
								</ul>
							</div>
							<div class="shipping-policies-wrapper">
								<div class="policies-row">
									<form class="form-horizontal">
										<div class="form-group">
											<label class="col-sm-5 control-label shipping-label no-pad-r"><span>Shipping price to SPAIN</span></label>
											<!--
											<div class="col-sm-5 pad-product">
												<select class="form-control selectpicker shipping-select product-select" title="Choose country">
													<option>USA</option>
													<option>SPAIN</option>
												</select>
											</div>
											-->
											<div class="col-sm-3">
												is <span class="tax">€<?=$product->getShippingPrice(Country::getDefaultContryCode())?></span>
											</div>
										</div>
									</form>
								</div>
								<?php
								$returns = $product->getReturnsLabel();
								$warranty = $product->getWarrantyLabel();
								?>
								<?php if ($returns) { ?>
									<div class="returns-row mt-30">
										<span translate="product.detail.RETURNS"></span>
										<span class="bold"><?=$returns?></span>
									</div>
								<?php } ?>
								<?php if ($warranty) { ?>
									<div class="returns-row">
										<span translate="product.detail.WARRANTY"></span>
										<span class="bold"><?=$warranty?></span>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /PRODUCT CARD -->
	<!-- PRODUCT DESCRIPTION -->
	<div class="product-description">
		<!-- Nav tabs -->
		<div class="container">
			<ul class="nav nav-tabs product-tabs" role="tablist">
				<li role="presentation" class="active no-b-r">
					<a href="#description" aria-controls="description" role="tab" data-toggle="tab"><span translate="product.detail.DESCRIPTION&REVIEWS"></span></a>
				</li>
				<?php if (count($videos)) { ?>
				<li role="presentation" class="no-b-r">
					<a href="#videos" aria-controls="works" role="tab" data-toggle="tab"><span translate="product.detail.VIDEOS"></span></a>
				</li>
				<?php } ?>
				<li role="presentation">
					<a href="#works" aria-controls="works" role="tab" data-toggle="tab"><span translate="product.detail.MORE_BY"></span><?= $person->getName() ?></a>
				</li>
			</ul>
		</div>
		<div class="container">
			<!-- Tab panes -->
			<div class="tab-content product-description-content">
				<div role="tabpanel" class="tab-pane work-description-wrapper active" id="description">
					<div class="work-profile-description-wrapper">
						<div class="col-sm-8 pad-product">
							<div class="title"><span translate="product.detail.DESCRIPTION"></span></div>
							<p class="description">
								<?= $product->description ?>
							</p>
							<?php if (count($product->mediaMapping->descriptionPhotosInfo) > 0) { ?>
								<div class="tb-wrapper">
									<div class="row">
										<?php foreach ($product->mediaMapping->descriptionPhotosInfo as $descriptionPhoto) { ?>
										<div class="col-md-3 work-profile-description-tb">
											<img src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getUrlImagesLocation().$descriptionPhoto->name)->resize(480, 0)?>">
											<span class="tb-title"><?= $descriptionPhoto->title?></span>
											<span class="tb-description"><?= $descriptionPhoto->description?></span>
										</div>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
						</div>
						<div class="col-sm-4">
							<div class="avatar-wrapper-side">
									<div class="avatar">
										<a href="<?= $person->getStoreLink() ?>">
											<img class="cover" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($person->getAvatarImage())->resize(128, 128) ?>" data-pin-nopin="true">
											<span><?= $person->getName() ?></span>
										</a>
									</div>
								</div>
						</div>
					</div>

					<?php if (count($product->faqMapping) > 0) { ?>
					   	<div class="work-profile-description-wrapper faq-wrapper">
						<div class="title"><span translate="product.detail.WORK_FAQS"></span></div>
						<?php foreach ($product->faqMapping as $faq) { ?>
							<div class="q-a-wrapper">
								<p class="question">
									<span translate="product.detail.Q"></span>
									<span class="important"><?= $faq->question?></span>
								</p>
								<p class="question">
									<span translate="product.detail.A"></span>
									<span><?= $faq->answer?></span>
								</p>
							</div>
						<?php } ?>
						</div>
					<?php } ?>

					<div class="reviews-wrapper">
						<div class="title"><span translate="product.detail.USER_REVIEWS"></span></div>
						<div class="review-rates">
							<span class="score">
									<i class="ion-ios-star"></i>
									<i class="ion-ios-star"></i>
									<i class="ion-ios-star"></i>
									<i class="ion-ios-star"></i>
									<i class="ion-ios-star"></i>
								</span>
							<span class="number-score">(20)</span>
							<div class="by-stars"><span>5 <span translate="product.detail.STARS"></span></span><span class="number-score">(15)</span></div>
							<div class="by-stars"><span>4 <span translate="product.detail.STARS"></span></span><span class="number-score">(5)</span></div>
						</div>
						<div class="comment-wrapper">
							<div class="col-sm-1">
								<div class="avatar">
									<img class="cover" src="/imgs/avatar-deviser.jpg">
								</div>
							</div>
							<div class="col-sm-10">
								<input type="text" class="form-control comment-input" id="exampleInputEmail1" placeholder="Add your comment">
								<div class="rate-product">
									<span translate="product.detail.RATE_PRODUCT"></span>
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
									<span class="date"><span>1</span><span translate="product.detail.DAY_AGO"></span>
								</div>
								<div class="comment-text">Vivamus ultricies mauris mi, nec imperdiet quam facilisis eget.
								</div>
								<div class="replay">
									<span translate="product.detail.REPLY"></span>
									<span class="score">
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
											<i class="ion-ios-star"></i>
										</span>
									<span class="useful"><span>300</span><span translate="product.detail.MEMER_COMMENT_USEFUL"></span></span>
								</div>
							</div>
							<div class="helpful">
								<span translate="product.detail.REVIEW_HELPFUL"></span>
								<div class="rounded-btn"><span translate="global.YES"></span></div>
								<div class="rounded-btn"><span translate="global.NO"></span></div>
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
								<span>Is this review helpful to you?</span>
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
							<span class="green" translate="product.detail.LOAD_MORE"></span>
							<span class="more"><span>24</span><span translate="product.detail.COMMENTS_MORE"></span></span>
						</div>
					</div>
				</div>
				<?php if (count($videos)) { ?>
					<div role="tabpanel" class="tab-pane work-description-wrapper" id="videos">
						<div class="other-products-wrapper">
							<div style="height:500px;">
								<div class="video-container">
									<?php foreach ($videos as $video) { ?>
										<div class="col-sm-6">
											<div class="video-wrapper">
												<iframe width="560" height="315" src="<?= $video->getUrlEmbeddedYoutubePlayer() ?>" frameborder="0" allowfullscreen></iframe>
											</div>
										</div>
									<?php }  ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<div role="tabpanel" class="tab-pane" id="works">
					<nav class="products-menu">
						<ul>
							<!--						<li>-->
							<!--							<a class="active" href="#">Pants</a>-->
							<!--						</li>-->
							<!--						<li>-->
							<!--							<a href="#">Socks</a>-->
							<!--						</li>-->
							<!--						<li>-->
							<!--							<a href="#">Belts</a>-->
							<!--						</li>-->
						</ul>
					</nav>
					<div class="other-products-wrapper">
						<div id="macy-container">
							<?php foreach ($personProducts as $i => $product) { ?>
								<div class="menu-category list-group">
									<div class="grid">
										<figure class="effect-zoe">
											<image-hover-buttons product-id="{{'<?= $product->short_id ?>'}}" is-loved="{{'<?=$product->isLovedByCurrentUser() ? 1 : 0 ?>'}}" is-mine="{{'<?= $product->isWorkFromCurrentUser() ? 1 : 0 ?>'}}">
												<a href="<?= $product->getViewLink() ?>">
													<img class="grid-image"
														 src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize(400, 0) ?>">
												</a>
											</image-hover-buttons>
											<a href="<?= $product->getViewLink() ?>">
												<figcaption>
													<p class="instauser">
														<?= $product->name ?>
													</p>
													<p class="price">€ <?= $product->getMinimumPrice() ?></p>
												</figcaption>
											</a>
										</figure>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /PRODUCT DESCRIPTION -->