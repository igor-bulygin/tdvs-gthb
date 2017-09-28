<?php

use app\assets\desktop\product\GlobalAsset;
use app\assets\desktop\pub\Product2Asset;
use app\helpers\Utils;
use app\models\Country;
use app\models\Person;
use app\models\PersonVideo;
use yii\helpers\Json;

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

$this->registerJs("var product = ".Json::encode($product), yii\web\View::POS_HEAD, 'product-var-script');

?>

	<!-- PRODUCT CARD -->
	
<div ng-controller="detailProductCtrl as detailProductCtrl">
	<div class="product">
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
									<td ng-repeat="column in detailProductCtrl.product.sizechart.columns"><span ng-bind="column[detailProductCtrl.lang]"></span></td>
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
								<div class="col-sm-2">
									<div id="arrow-up">
										<span class="ion-ios-arrow-up"></span>
									</div>
									<ul class='carousel-indicators thumbs mCustomScrollbar'>
										<?php foreach ($productImages as $key => $imageUrl) { ?>
											<li class="col-sm-2" data-target='#carousel-custom' data-slide-to='<?= $key ?>' class='active'>
												<img src='<?= Utils::url_scheme() ?><?= Utils::thumborize($imageUrl)->resize(410, 0) ?>' alt='' />
											</li>
											<?php } ?>
									</ul>
									<div id="arrow-down">
										<span class="ion-ios-arrow-down"></span>
									</div>
								</div>
								<div class="col-sm-10">
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
											<span class='ion-ios-arrow-left arrow'></span>
										</a>
										<a class='right carousel-control' href='#carousel-custom' data-slide='next'>
											<span class='ion-ios-arrow-right arrow'></span>
										</a>
										<?php } ?>
									</div>
								</div>
								<?php /*
								<div class="col-sm-12">
									<ol class='carousel-indicators thumbs mCustomScrollbar'>
										<?php foreach ($productImages as $key => $imageUrl) { ?>
											<li class="col-sm-1" data-target='#carousel-custom' data-slide-to='<?= $key ?>' class='active'>
												<img src='<?= Utils::url_scheme() ?><?= Utils::thumborize($imageUrl)->resize(410, 0) ?>' alt='' />
											</li>
											<?php } ?>
									</ol>
								</div>
								*/?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 pad-product">
					<div class="product-data-wrapper">
						<div class="product-data">
							<span class="title" ng-bind="detailProductCtrl.product.name"></span>
							<?php /*
							<span class="score">
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
							</span>
							<span class="number-score">(20)</span>
							*/ ?>
						</div>
						<div class="product-data no-border">
							<div class="price-stock pull-left">
								<div class="stock"><span ng-bind="detailProductCtrl.stock"></span><span translate="product.detail.IN_STOCK"></span></div>
								<div class="product-price">€ <span ng-bind="detailProductCtrl.price"></span></div>
							</div>
							<div class="quantity-wrapper pull-right">
								<button class="btn btn-none btn-summatory" ng-click="detailProductCtrl.changeQuantity(-1)">
									<i class="ion-minus"></i>
								</button>
								<div class="number" ng-bind="detailProductCtrl.quantity"></div>
								<button class="btn btn-none btn-summatory" ng-click="detailProductCtrl.changeQuantity(1)">
									<i class="ion-plus"></i>
								</button>
							</div>
						</div>
						<div class="product-data no-border">
							<ul class="nav nav-tabs product-detail-tabs" role="tablist" ng-if="detailProductCtrl.original_artwork && detailProductCtrl.has_prints" ng-cloak>
								<li role="presentation" class="active">
									<a href="#" aria-controls="description" role="tab" data-toggle="tab" ng-click="detailProductCtrl.changeOriginalArtwork(true)"><span translate="product.detail.ORIGINAL"></span></a>
								</li>
								<li role="presentation">
									<a href="#" aria-controls="works" role="tab" data-toggle="tab" ng-click="detailProductCtrl.changeOriginalArtwork(false)"><span translate="product.detail.PRINTS"></span></a>
								</li>
							</ul>
							<div>
								<form name="detailProductCtrl.tagsForm">
									<div class="form-horizontal">
										<div class="form-group">
											<div class="row-size expand" ng-repeat="option in detailProductCtrl.product.options | orderBy:[detailProductCtrl.selectComparator]" ng-if="option.values.length >= 1" ng-cloak>
												<label class="col-sm-3 control-label product-label"><span class="atr" ng-bind="option.name | translate"></span></label>
												<div class="col-sm-9" ng-if="option.values.length > 1 && option.change_reference" ng-cloak>
													<div class="row">
														<div class="col-sm-8">
															<ol name="{{option.id}}" class="nya-bs-select btn-group bootstrap-select form-control product-select ng-class:{'error-input': detailProductCtrl.has_error(detailProductCtrl.tagsForm, detailProductCtrl.tagsForm[option.id])}" ng-model="detailProductCtrl.option_selected[option.id]" ng-change="detailProductCtrl.optionsChanged(option.id, detailProductCtrl.option_selected[option.id])" ng-required="detailProductCtrl.require_options" ng-cloak>
																<li nya-bs-option="value in option.values" data-value="value.value" ng-class="{'disabled': value.disabled}">
																	<a href="">
																		<span ng-bind="value.text"></span>
																	</a>
																</li>
															</ol>
														</div>
														<div class="col-sm-4 no-pad">
															<?php /*
															<a class="view-chart-size" href="#" href="#" data-toggle="modal" data-target="#chartModal" ng-if="option.id==='size' && detailProductCtrl.view_sizechart"><span translate="product.detail.VIEW_SIZE_CHART"></span></a>
															*/?>
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
								<button type="button" class="btn btn-medium btn-red auto-center" ng-disabled="detailProductCtrl.stock === 0" ng-click="detailProductCtrl.addToCart(detailProductCtrl.tagsForm)"><span class="cart-icon"></span> <span translate="{{detailProductCtrl.stock === 0 ? 'product.detail.OUT_OF_STOCK' : 'product.detail.ADD_TO_CART'}}"></span></button>
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
							<div class="full-width mb-20">
								<div class="btns-product-wrapper">
									<?php if ($product->isWorkFromCurrentUser()) { ?>
										<a href="<?=$product->getEditLink()?>" class="btn btn-hart pull-left">
											<i class="ion-edit"></i>
											<span translate="product.detail.EDIT_WORK"></span>
										</a>
									<?php } else { ?>
										<button type="button" class="btn btn-love pull-left" ng-class="detailProductCtrl.product.isLoved ? 'heart-red-icon-btn' : 'btn-love'" ng-click="detailProductCtrl.setLoved()">
											<div class="heart-icon"></div>
											<!--i class="ion-ios-heart-outline"></i-->
										</button>
									<?php } ?>
									<button type="button" class="btn btn-save-box pull-right" ng-click="detailProductCtrl.setBox()">
										<div class="box-icon"></div>
										<span translate="product.detail.SAVE_IN_BOX"></span>
									</button>
								</div>
							</div>
							<!--<div class="row-size">
								<span class="btn-tagline loved pull-left" translate="product.detail.LOVED"></span>
								<span ng-bind="detailProductCtrl.product.loveds"></span><span translate="product.detail.TIMES"></span>
								<span class="btn-tagline saved pull-right" translate="product.detail.SAVED_IN_X_BOXES" translate-values="{ x:detailProductCtrl.product.boxes}"></span>
							</div>-->
							<div class="product-data">
								<div class="full-width mb-20">
									<div class="btns-product-wrapper">
										<ul class="social-items">
											<li>
												<a href="https://facebook.com/todevise">
													<i class="facebook">
														<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
															 x="0px" y="0px"
															 viewBox="0 0 23 42" style="enable-background:new 0 0 23 42;" xml:space="preserve">
														<g id="Page-1">
															<path id="Path" class="st0" d="M14.3,41V21h5.9l0.8-6.9h-6.7l0-3.4c0-1.8,0.2-2.8,3-2.8H21V1H15c-7.1,0-9.6,3.3-9.6,9v4.1H1V21h4.4
																v20H14.3L14.3,41L14.3,41L14.3,41z"/>
														</g>
														</svg>
													</i>
												</a>
											</li>
											
											<li>
												<a class="twitter" href="https://twitter.com/todevise">
													<i class="twitter">
														<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
															 x="0px" y="0px"
															 viewBox="0 0 49 42" style="enable-background:new 0 0 49 42;" xml:space="preserve">
														<g id="Page-1">
															<path id="Path" class="st0" d="M23.5,11.6l0.1,1.7l-1.7-0.2C15.8,12.3,10.5,9.6,6,5L3.8,2.7L3.2,4.4C2,8.1,2.8,12.1,5.3,14.8
																c1.3,1.5,1,1.7-1.3,0.8c-0.8-0.3-1.5-0.5-1.6-0.4C2.2,15.5,3,18.7,3.6,20c0.9,1.8,2.6,3.5,4.6,4.5l1.6,0.8l-1.9,0
																c-1.9,0-1.9,0-1.7,0.8c0.7,2.3,3.3,4.7,6.3,5.8l2.1,0.7l-1.8,1.1c-2.7,1.6-5.8,2.5-9,2.6c-1.5,0-2.7,0.2-2.7,0.3
																C1,37,5.1,39,7.5,39.8c7.1,2.3,15.6,1.3,22-2.6c4.5-2.8,9-8.3,11.1-13.7c1.1-2.9,2.3-8.1,2.3-10.6c0-1.6,0.1-1.8,2-3.8
																c1.1-1.1,2.1-2.4,2.3-2.7c0.3-0.7,0.3-0.7-1.4-0.1c-2.8,1.1-3.2,0.9-1.8-0.7c1-1.1,2.3-3.2,2.3-3.8c0-0.1-0.5,0.1-1.1,0.4
																c-0.6,0.4-1.9,0.9-2.9,1.2L40.4,4l-1.6-1.2c-0.9-0.6-2.2-1.3-2.8-1.6c-1.7-0.5-4.3-0.4-5.9,0.1C25.8,3.1,23.2,7.2,23.5,11.6
																C23.5,11.6,23.2,7.2,23.5,11.6L23.5,11.6L23.5,11.6z"/>
														</g>
														</svg>
													</i>
												</a>
											</li>

											<li>
												<a class="instagram" href="https://www.instagram.com/todevise.official">
													<i class="instagram">
														<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
															 x="0px" y="0px"
															 viewBox="0 0 49 42" style="enable-background:new 0 0 49 42;" xml:space="preserve">
															<g>
																<path id="Path" class="st0" d="M27.5,40.5h-16c-7.2,0-13-5.8-13-13v-16c0-7.2,5.8-13,13-13h16c7.2,0,13,5.8,13,13v16
																	C40.5,34.7,34.7,40.5,27.5,40.5z M11.5,0.5c-6.1,0-11,4.9-11,11v16c0,6.1,4.9,11,11,11h16c6.1,0,11-4.9,11-11v-16
																	c0-6.1-4.9-11-11-11H11.5z"/>
															</g>
															<g>
																<path id="Path" class="st0" d="M19.5,29.6c-5.6,0-10.1-4.5-10.1-10.1c0-5.6,4.5-10.1,10.1-10.1c5.6,0,10.1,4.5,10.1,10.1
																	C29.6,25.1,25.1,29.6,19.5,29.6z M19.5,11.4c-4.5,0-8.1,3.6-8.1,8.1s3.6,8.1,8.1,8.1s8.1-3.6,8.1-8.1S24,11.4,19.5,11.4z"/>
															</g>
															<g>
																<circle id="Path" class="st0" cx="30.5" cy="10" r="1.6"/>
															</g>
														</svg>
													</i>
												</a>
											</li>
											<!--li>
												<a class="pinterest" href="#">
													<i class="pinterest" aria-hidden="true">
														<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
															 x="0px" y="0px"
															 viewBox="0 0 32 42" style="enable-background:new 0 0 32 42;" xml:space="preserve">
														<g id="Page-1">
															<path id="Path" class="st0" d="M1,15.4c0,4,1.4,7.5,4.4,8.8c0.5,0.2,0.9,0,1.1-0.6c0.1-0.4,0.3-1.4,0.4-1.8
																c0.1-0.6,0.1-0.8-0.3-1.3c-0.9-1.1-1.4-2.5-1.4-4.5c0-5.8,4.1-11,10.7-11c5.8,0,9,3.8,9,8.8c0,6.6-2.8,12.2-6.9,12.2
																c-2.3,0-4-2-3.4-4.4c0.7-2.9,1.9-6.1,1.9-8.2c0-1.9-1-3.4-2.9-3.4c-2.3,0-4.2,2.5-4.2,5.9c0,2.2,0.7,3.6,0.7,3.6S7.8,30.2,7.3,32
																c-0.8,3.7-0.1,8.3-0.1,8.7c0,0.3,0.4,0.3,0.5,0.1c0.2-0.3,3-3.9,3.9-7.5c0.3-1,1.5-6.3,1.5-6.3c0.8,1.5,3,2.9,5.3,2.9
																c7,0,11.7-6.7,11.7-15.7C30.2,7.3,24.8,1,16.5,1C6.2,1,1,8.8,1,15.4z"/>
														</g>
														</svg>
													</i>
												</a>
											</li-->
										</ul>
									</div>
								</div>
							</div>
							<div class="full-width mt-20">	
								<div class="bs-example">
									<p>
										<a href="#collapseExample" class="btn collapsed accordion-btn" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="collapseExample"> <span translate="product.detail.SHIPPING&POLICIES"></span> <i class="ion-plus"></i> </a>
									</p>
									<div class="collapse" id="collapseExample" aria-expanded="false" style="height: 0px;">
										<div class="shipping-policies-wrapper">
								<div class="policies-row">
									<form class="form-horizontal">
										<div class="form-group">
											<label class="col-sm-5 control-label shipping-label no-pad-r"><span translate="product.detail.SHIPPING_PRICE_SPAIN"></span></label>
											<!--
											<div class="col-sm-5 pad-product">
												<select class="form-control selectpicker shipping-select product-select" title="Choose country">
													<option>USA</option>
													<option>SPAIN</option>
												</select>
											</div>
											-->
											<div class="col-sm-3">
												<span translate="product.detail.IS"></span> <span class="tax">€<?=$product->getShippingPrice(null, Country::getDefaultContryCode())?></span>
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
			</div>
		</div>
	</div>
	<!-- /PRODUCT CARD -->
	<!-- PRODUCT DESCRIPTION -->
	<div class="product-description">
		<!-- Nav tabs -->
		<div class="container">
			<div class="work-profile-description-wrapper">
						<div class="title mb-40"><span class="title-product-name" translate="product.detail.DESCRIPTION"></span></div>
						<div class="col-sm-9 pad-product">
							<div class="description-parraf">
								<?= $product->description ?>
							</div>
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
						<div class="col-sm-3">
							<div class="avatar-wrapper-side">
									<div class="avatar">
										<a href="<?= $person->getStoreLink() ?>">
											<span translate="product.detail.CREATED_BY"></span>
											<img class="avatar-default medium" src="<?= $person->getProfileImage(128, 128) ?>" data-pin-nopin="true">
											<!--<span><?= $person->getName() ?></span>-->
										</a>
									</div>
								</div>
						</div>
					</div>
		</div>
		<div class="container">
			<ul class="nav nav-tabs product-tabs" role="tablist" id="productTabs">
				<li role="presentation" class="active">
					<a href="#works" aria-controls="works" role="tab" data-toggle="tab"><span class="title-product-name">works by <?= \yii\helpers\StringHelper::truncate($person->getName(), 40, '…') ?></span></a>
				</li>
				<li role="presentation" class="no-b-r">
					<a href="#boxes" aria-controls="boxes" role="tab" data-toggle="tab"><span class="title-product-name">Boxes</span></a>
				</li>
				<? /*php if (count($videos)) {*/ ?>
				<li role="presentation" class="no-b-r">
					<a href="#videos" aria-controls="videos" role="tab" data-toggle="tab"><span translate="product.detail.VIDEOS" class="title-product-name"></span></a>
				</li>
				<?php /*}*/ ?>
				<?php /*if (count($product->faqMapping) > 0) {*/ ?>
				<li role="presentation" class="no-b-r">
					<a href="#faqs" aria-controls="faqs" role="tab" data-toggle="tab"><span class="title-product-name">FAQs</span></a>
				</li>
				<?php /*}*/?>
			</ul>
		</div>
		<div class="container">
			<!-- Tab panes -->
			<div class="tab-content product-description-content">
				<div role="tabpanel" class="tab-pane work-description-wrapper" id="faqs">
					<div class="container mt-20 mb-20">
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
						<?php } else { ?>
							<div class="col-lg-12 centered-col">
								<img class="happyface-black" src="/imgs/happy-face-black.svg" />
								<span translate="product.detail.MORECONTENTCOMINGSOON"></span>
							</div>
						<?php } ?>

					</div>
				</div>
				<div role="tabpanel" class="tab-pane work-description-wrapper" id="boxes">
					<div class="container mt-20 mb-20">
						<?php if ($boxes) { ?>
							<?php foreach ($boxes as $box) {
								$products = $box->getProductsPreview(); ?>
								<div class="col-lg-4">
									<a href="<?= $box->getViewLink()?>">
										<figure class="showcase">
											<div class="images-box">
												<div class="bottom-top-images">
													<div class="image-left">
														<img src="<?=isset($products[0]) ? $products[0]['main_photo'] : 'imgs/img-default.jpg'?>" class="showcase-image">
													</div>
													<div class="image-right">
														<img src="<?=isset($products[1]) ? $products[1]['main_photo'] : 'imgs/img-default.jpg'?>" class="showcase-image">
													</div>
												</div>
												<div class="bottom-image">
													<img src="<?=isset($products[2]) ? $products[2]['main_photo'] : 'imgs/img-default.jpg'?>" class="showcase-image">
												</div>
											</div>
											<figcaption>
												<div class="row no-mar">
													<div class="col-md-8">
														<span class="boxes-text align-left"><?=$box->name?></span>
													</div>
													<div class="col-md-4 no-padding">
														<button class="btn btn-single-love btn-love-box">
															<span class="number"><?=count($products)?></span>
															<span class="heart-icon"></span>
														</button>
													</div>
												</div>
											</figcaption>
										</figure>
									</a>
								</div>
							<?php } ?>
						<?php } else { ?>
							<div class="col-lg-12 centered-col">
								<button type="button" class="btn btn-red btn-hart" ng-click="detailProductCtrl.setBox()">
									<span translate="product.detail.SAVE_IN_BOX"></span>
								</button>
							</div>
						<?php } ?>
					</div>

					<!--<div class="reviews-wrapper">
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
					</div>-->
				</div>
				<?php /*if (count($videos)) { */?>
					<div role="tabpanel" class="tab-pane work-description-wrapper" id="videos">
						<div class="container mt-20 mb-20">
							<?php if (count($videos)) { ?>
								<div class="video-container centered-col">
									<?php foreach ($videos as $video) { ?>
										<div class="col-sm-6">
											<div class="video-wrapper">
												<iframe width="560" height="315" src="<?= $video->getUrlEmbeddedYoutubePlayer() ?>" frameborder="0" allowfullscreen></iframe>
											</div>
										</div>
									<?php }  ?>
								</div>
							<?php } else {?>
								<div class="col-lg-12 centered-col">
									<img class="happyface-black" src="/imgs/happy-face-black.svg" />
									<span translate="product.detail.MORECONTENTCOMINGSOON"></span>
								</div>
							<?php }?>
						</div>
					</div>
				<?php /*} */?>
				<div role="tabpanel" class="tab-pane work-description-wrapper active" id="works">
					<div class="container mt-20 mb-20" style="min-height:350px;">
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
						<div id="works-container" class="macy-container" data-columns="6">
							<?php foreach ($personProducts as $i => $product) { ?>
								<div class="menu-category list-group">
									<div class="grid">
										<figure class="effect-zoe">
											<image-hover-buttons product-id="{{'<?= $product->short_id ?>'}}" is-loved="{{'<?=$product->isLovedByCurrentUser() ? 1 : 0 ?>'}}" is-mine="{{'<?= $product->isWorkFromCurrentUser() ? 1 : 0 ?>'}}">
												<a href="<?= $product->getViewLink() ?>">
													<img class="grid-image"
														 src="<?= $product->getImagePreview(400, 0) ?>">
												</a>
											</image-hover-buttons>
											<a href="<?= $product->getViewLink() ?>">
												<figcaption>
													<p class="instauser">
														<?= \yii\helpers\StringHelper::truncate($product->getName(), 18, '…') ?>
														<!--<?= \yii\helpers\StringHelper::truncate(Utils::l($product->getName()), 18, '…') ?>-->
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
	</div>
</div>
	<!-- /PRODUCT DESCRIPTION -->
