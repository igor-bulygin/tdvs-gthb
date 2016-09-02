<?php
use app\assets\desktop\pub\Product2Asset;
use app\assets\desktop\pub\PublicCommonAsset;
use app\models\Category;
use app\models\Person;
use app\models\Product;
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

Product2Asset::register($this);

/** @var Person $deviser */
/** @var Product $product */
/** @var Tag $tag */

$this->title = Utils::l($product->name) . ' - Todevise';
$productImages = $product->getUrlGalleryImages();

$this->registerCss(".product-photo-bg { background-image: url(" . Utils::url_scheme() . Utils::thumborize($product->getMainImage())->resize(800, 0) . "); }");

// TODO fix the carrousel to avoid css hack here
$cssCarrouselFix = sprintf("
	#slide%s:checked ~ #cc-slides .inner {
        margin-left: 0!important;  
    }", (count($productImages)+1));

//$this->registerCss($cssCarrouselFix);
?>


	<!-- PRODUCT CARD -->
	<div class="product">
		<div class="container">
			<div class="row">
				<div class="col-md-9 pad-product">
					<div class="product-photo-bg">
					</div>
					<div class="product-photos-wrapper">
						<div class="slider-header">
							<span class="title"><?= Utils::l($product->name) ?></span>
							<span class="score">
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<!--<i class="ion-ios-star-outline"></i>-->
							</span>
							<span class="number-score">(20)</span>
							<span class="like-comments">
								<span class="like">
									<i class="ion-heart"></i>
								<span>342</span>
							</span>
							<span class="comments">
								<i class="ion-chatbox"></i>
								<span>15</span>
							</span>
							</span>
						</div>

						<article id="cc-slider">
							<input checked="checked" name="cc-slider" id="slide1" type="radio">
							<input name="cc-slider" id="slide2" type="radio">
							<input name="cc-slider" id="slide3" type="radio">
							<input name="cc-slider" id="slide4" type="radio">
							<input name="cc-slider" id="slide5" type="radio">
							<div id="cc-slides">
								<div id="overflow">
									<div class="inner">
										<?php foreach ($productImages as $imageUrl) { ?>
											<article>
												<img src="<?= Utils::url_scheme() ?><?= Utils::thumborize($imageUrl)->resize(410, 0) ?>">
											</article>
											<?php } ?>
									</div>
								</div>
							</div>
							<div id="controls">
								<label for="slide1">
									<i class="ion-ios-arrow-right arrow"></i>
								</label>
								<label for="slide2">
									<i class="ion-ios-arrow-right arrow"></i>
								</label>
								<label for="slide3">
									<i class="ion-ios-arrow-right arrow"></i>
								</label>
								<label for="slide4">
									<i class="ion-ios-arrow-right arrow"></i>
								</label>
								<label for="slide5">
									<i class="ion-ios-arrow-right arrow"></i>
								</label>
							</div>
						</article>
					</div>

				</div>
				<div class="col-md-3 pad-product" ng-controller="detailProductCtrl as detailProductCtrl">
					<div class="product-data-wrapper">
						<div class="product-data">
							<div class="avatar">
								<a href="<?= Url::to([" deviser/store ", "slug " => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">
							<img class="cover" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getAvatarImage())->resize(128, 128) ?>">
							</a>
							</div>
							<div class="name-cathegory">
								<div class="cathegory">
									<?= Utils::l($product->getCategory(2)->name) ?>
								</div>
								<div class="name">
									<?= $deviser->getBrandName() ?>
								</div>
							</div>
							<div class="price-stock">
								<div class="stock">6 in stock</div>
								<div class="product-price">€
									<span ng-bind="detailProductCtrl.minimum_price"></span>
								</div>
							</div>
						</div>
						<div class="product-data grey">
							<div class="row-size" ng-repeat="option in detailProductCtrl.product.options">
								<form class="form-horizontal">
									<div class="form-group">
										<label class="col-sm-3 control-label product-label"><span class="atr" ng-bind="option.name"></span></label>
										<div class="col-sm-9">
											<div ng-if="option.widget_type === 'select'">
												<select class="form-control selectpicker product-select" ng-attr-title="Choose {{option.name}}" ng-attr-name="option.name" ng-model="detailProductCtrl.options[option.name]" ng-options="option.value as option.text for option in option.values">
													<option value="" style="display:none"></option>
												</select>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
<!--						<div class="row-size">-->
<!--							<form class="form-horizontal">-->
<!--								<div class="form-group">-->
<!--									<label class="col-sm-3 control-label product-label"><span class="atr">Size</span> <i class="ion-information-circled info"></i></label>-->
<!--									<div class="col-sm-9">-->
<!--										<select class="form-control selectpicker product-select" title="Choose size">-->
<!--											<option></option>-->
<!--											<option>XS (+ € 1.95)</option>-->
<!--											<option>S (+ € 10.95)</option>-->
<!--											<option>M (+ € 10.95)</option>-->
<!--											<option>L (+ € 10.95)</option>-->
<!--											<option>XL (+ € 10.95)</option>-->
<!--											<option>XXL (+ € 10.95)</option>-->
<!--										</select>-->
<!--									</div>-->
<!--								</div>-->
<!--							</form>-->
<!--						</div>-->
<!--						<div class="row-size">-->
<!--							<form class="form-horizontal">-->
<!--								<div class="form-group">-->
<!--									<label class="col-sm-3 control-label product-label"><span class="atr">Color</span></label>-->
<!--									<div class="col-sm-9">-->
<!--										<select class="form-control selectpicker product-select" title="Choose color">-->
<!--											<option></option>-->
<!--											<option>Blue </option>-->
<!--											<option>Yellow</option>-->
<!--											<option>Red</option>-->
<!--											<option>Orange</option>-->
<!--											<option>Green</option>-->
<!--										</select>-->
<!--									</div>-->
<!--								</div>-->
<!--							</form>-->
<!--						</div>-->
<!--						<div class="row-size">-->
<!--							<form class="form-horizontal">-->
<!--								<div class="form-group">-->
<!--									<label class="col-sm-3 control-label product-label"><span class="atr">Material</span></label>-->
<!--									<div class="col-sm-9">-->
<!--										<select class="form-control selectpicker product-select" title="Choose material">-->
<!--											<option></option>-->
<!--											<option>Wool (+ € 10.95)</option>-->
<!--											<option>Fabric (+ € 5.95)</option>-->
<!--										</select>-->
<!--									</div>-->
<!--								</div>-->
<!--							</form>-->
<!--						</div>-->
<!--						<div class="row-size">-->
<!--							<form class="form-horizontal">-->
<!--								<div class="form-group">-->
<!--									<label class="col-sm-3 control-label product-label"><span class="atr">Band</span></label>-->
<!--									<div class="col-sm-9">-->
<!--										<select class="form-control selectpicker product-select" title="Choose band">-->
<!--											<option></option>-->
<!--											<option>Leather (+ € 20.95)</option>-->
<!--											<option>Silicone (+ € 5.95)</option>-->
<!--										</select>-->
<!--									</div>-->
<!--								</div>-->
<!--							</form>-->
<!--						</div>-->
<!--						<div class="row-size">-->
<!--							<form class="form-horizontal">-->
<!--								<div class="form-group">-->
<!--									<label class="col-sm-3 control-label product-label"><span class="atr">Diameter</span></label>-->
<!--									<div class="col-sm-9">-->
<!--										<select class="form-control selectpicker product-select" title="Choose diameter">-->
<!--											<option></option>-->
<!--											<option>20 cm (+ € 11.95)</option>-->
<!--											<option>30 cm (+ € 5.95)</option>-->
<!--										</select>-->
<!--									</div>-->
<!--								</div>-->
<!--							</form>-->
<!--						</div>-->
<!--						<div class="row-size">-->
<!--							<form class="form-horizontal">-->
<!--								<div class="form-group">-->
<!--									<label class="col-sm-3 control-label product-label"><span class="atr">Material</span></label>-->
<!--									<div class="col-sm-9">-->
<!--										<div class="atribute-selected">-->
<!--											Wool-->
<!--										</div>-->
<!--									</div>-->
<!--								</div>-->
<!--							</form>-->
<!--						</div>-->
						<div class="product-data grey">
							<div class="row-size">
								<span class="atr quantity-name">Quantity</span>
								<div class="number">1</div>
								<div class="plus">+</div>
								<div class="minus">-</div>
							</div>
						</div>
						<div class="product-data grey">
							<div class="row-size">
								<button type="button" class="btn add-to-cart-btn"><i class="ion-ios-cart cart-icon-btn"></i> <span>Add to cart</span></button>
								<div class="shipping-policies-wrapper">
									<div class="title">Shipping &amp; Policies</div>
									<div class="policies-row">
										<form class="form-horizontal">
											<div class="form-group">
												<label class="col-sm-4 control-label shipping-label"><span>Shipping price</span></label>
												<div class="col-sm-6 shipping-sel">

													<select class="form-control selectpicker shipping-select product-select" title="Choose country">
														<option>USA</option>
														<option>SPAIN</option>
													</select>

												</div>
												<div class="col-sm-2">
													<span class="tax">€4.5</span>
												</div>
											</div>
										</form>
									</div>
									<div class="returns-row">
										Returns: 14 days
									</div>
									<div class="returns-row">
										Warranty:
										<?= $product->getWarrantyLabel() ?>
									</div>
								</div>
							</div>
						</div>
						<div class="product-data no-border">
							<div class="row-size">
								<button type="button" class="btn btn-grey pull-left">
									<i class="ion-ios-heart"></i>
									<span>Love work</span>
								</button>
								<button type="button" class="btn btn-grey pull-right">
									<i class="ion-ios-box"></i>
									<span>Save in a box</span>
								</button>
							</div>
							<div>
								<ul class="social-items">
									<li>
										<span>Share on</span>
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
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /PRODUCT CARD -->
	<!-- PRODUCT DESCRIPTION -->
	<div class="product-description">
		<div class="container">
			<!-- Nav tabs -->
			<ul class="nav nav-tabs product-tabs" role="tablist">
				<li role="presentation" class="active">
					<a href="#description" aria-controls="description" role="tab" data-toggle="tab">Description &amp; User reviews</a>
				</li>
				<li role="presentation">
					<a href="#works" aria-controls="works" role="tab" data-toggle="tab">More works by <?= $deviser->getBrandName() ?></a>
				</li>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content product-description-content">
				<div role="tabpanel" class="tab-pane active work-description-wrapper" id="description">
					<div class="title">Work Description</div>
					<p class="description">
						<?= Utils::l($product->description) ?>
					</p>
					<div class="title">WORK FAQs</div>
					<div class="q-a-wrapper">
						<p class="question">
							<span>Q:</span>
							<span class="important">How does it work?</span>
						</p>
						<p class="question">
							<span>A:</span>
							<span>It’s the best product ever. It’s the best product ever. It’s the best product ever. It’s the best product ever. It’s the best product ever. It’s the best product ever. It’s the best product ever. It’s the best product ever. It’s the best product ever. It’s the best product ever. It’s the best product ever. It’s the best product ever.</span>
						</p>
					</div>
					<div class="q-a-wrapper">
						<p class="question">
							<span>Q:</span>
							<span class="important">How many screens does it have?</span>
						</p>
						<p class="question">
							<span>A:</span>
							<span>100</span>
						</p>
					</div>
					<div class="q-a-wrapper">
						<p class="question">
							<span>Q:</span>
							<span class="important">Does it have worldwide warranty?</span>
						</p>
						<p class="question">
							<span>A:</span>
							<span>Yes</span>
						</p>
					</div>
					<div class="reviews-wrapper">
						<div class="title">User reviews</div>
						<div class="review-rates">
							<span class="score">
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<!--<i class="ion-ios-star-outline"></i>-->
							</span>
							<span class="number-score">(20)</span>
							<div class="by-stars"><span>5 stars</span> <span class="number-score">(15)</span></div>
							<div class="by-stars"><span>4 stars</span> <span class="number-score">(5)</span></div>
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
									<span>Rate this product</span>
									<span class="score">
										<i class="ion-ios-star-outline"></i>
										<i class="ion-ios-star-outline"></i>
										<i class="ion-ios-star-outline"></i>
										<i class="ion-ios-star-outline"></i>
										<i class="ion-ios-star-outline"></i>
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
								<div class="comment-text">Vivamus ultricies mauris mi, nec imperdiet quam facilisis eget.</div>
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
								<div class="comment-text">Vivamus ultricies mauris mi, nec imperdiet quam facilisis eget.</div>
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
								<div class="comment-text">Vivamus ultricies mauris mi, nec imperdiet quam facilisis eget ultricies mauris mi, nec imperdiet quam facilisis eget ultricies mauris mi, nec imperdiet quam facilisis eget ultricies mauris mi, nec imperdiet quam facilisis eget ultricies mauris mi, nec imperdiet quam facilisis eget ultricies mauris mi, nec imperdiet quam facilisis eget.</div>
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
								<div class="comment-text">Vivamus ultricies mauris mi, nec imperdiet quam facilisis eget.</div>
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
							<span class="green">LOAD MORE</span>
							<span class="more">24 comments more</span>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="works">
					<nav class="products-menu">
						<!--					<ul>-->
						<!--						<li>-->
						<!--							<a class="active" href="#">Pants</a>-->
						<!--						</li>-->
						<!--						<li>-->
						<!--							<a href="#">Socks</a>-->
						<!--						</li>-->
						<!--						<li>-->
						<!--							<a href="#">Belts</a>-->
						<!--						</li>-->
						<!--					</ul>-->
					</nav>
					<div class="mesonry-row">
						<?php foreach ($deviserProducts as $i => $product) { ?>
							<div class="menu-category list-group">
								<a href="<?= Url::to([" product/detail ", "slug " => Utils::l($product->slug), 'product_id' => $product->short_id])?>">
									<div class="grid">
										<figure class="effect-zoe">
											<img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize(400, 0) ?>">
											<figcaption>
												<p class="instauser">
													<?= Utils::l($product->name) ?>
												</p>
												<p class="price">€
													<?= $product->getMinimumPrice() ?>
												</p>
											</figcaption>
										</figure>
									</div>
								</a>
							</div>
							<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /PRODUCT DESCRIPTION -->