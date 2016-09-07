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

$this->title = $product->name . ' - Todevise';
$productImages = $product->getUrlGalleryImages();

?>

	<!-- PRODUCT CARD -->
	<div class="product">
		<div class="container">
			<div class="row">
				<div class="col-md-8 pad-product">
					<div class="product-photos-wrapper">
						<!-- CAROUSEL-->
						<div id='carousel-custom' class='carousel slide' data-ride='carousel'>
							<!-- Indicators -->
							<div class="row">
								<div class="col-sm-1 pad-car">
									<ol class='carousel-indicators thumbs mCustomScrollbar'>
										<?php foreach ($productImages as $key => $imageUrl) { ?>
											<li data-target='#carousel-custom' data-slide-to='<?= $key ?>' class='active'>
												<img src='<?= Utils::url_scheme() ?><?= Utils::thumborize($imageUrl)->resize(410, 0) ?>' alt='' />
											</li>
											<?php } ?>
									</ol>
								</div>
								<div class="col-sm-11">
									<div class='carousel-outer'>
										<!-- Wrapper for slides -->
										<div class='carousel-inner'>
											<?php foreach ($productImages as $key => $imageUrl) { ?>
												<div class='item <?= ($key==0) ? ' active ' : ' ' ?>'>
													<img class="product-slide" src='<?= Utils::url_scheme() ?><?= Utils::thumborize($imageUrl)->resize(410, 0) ?>' alt='' />
												</div>
												<?php } ?>
										</div>
										<!-- Controls -->
										<a class='left carousel-control' href='#carousel-custom' data-slide='prev'>
											<span class='ion-ios-arrow-left arrow'>
                                                    </span>
										</a>
										<a class='right carousel-control' href='#carousel-custom' data-slide='next'>
											<span class='ion-ios-arrow-right arrow'>
                                                    </span>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 pad-product" ng-controller="detailProductCtrl as detailProductCtrl">
					<div class="product-data-wrapper">
						<div class="product-data">
							<span class="title" ng-bind="detailProductCtrl.product.name"></span>
							<span class="score">
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star"></i>
								<i class="ion-ios-star-outline"></i>
							</span>
							<span class="number-score">(20)</span>
						</div>
						<div class="product-data">
							<div class="price-stock pull-left">
								<div class="stock">6 in stock</div>
								<div class="product-price">€ <span ng-bind="detailProductCtrl.price"></span></div>
							</div>
							<div class="quantity-wrapper pull-right">
								<span class="atr quantity-name">Quantity</span>
								<div class="number" ng-bind="detailProductCtrl.quantity"></div>
								<button class="btn btn-green btn-summatory">
									<span>+</span>
								</button>
								<button class="btn btn-green btn-summatory">
									<span>-</span>
								</button>
							</div>
						</div>
						<div class="product-data">
							<!--
							<div class="row-size">
								<form class="form-horizontal">
									<div class="form-group">
										<label class="col-sm-3 control-label product-label"><span class="atr">Size</span> <i class="ion-information-circled info"></i></label>
										<div class="col-sm-9">
											<div class="size-box" data-toggle="tooltip" data-placement="top" title="+ €5,50">
												<input type="radio" name="size" id="size-1">
												<label for="size-1">
													<span class="radio">XS</span> </label>
											</div>
											<div class="size-box" data-toggle="tooltip" data-placement="top" title="+ €6,50">
												<input type="radio" name="size" id="size-2">
												<label for="size-2">
													<span class="radio">S</span> </label>
											</div>
											<div class="size-box" data-toggle="tooltip" data-placement="top" title="+ €8,50">
												<input type="radio" name="size" id="size-3">
												<label for="size-3">
													<span class="radio">M</span> </label>
											</div>
											<div class="size-box" data-toggle="tooltip" data-placement="top" title="+ €4,50">
												<input type="radio" name="size" id="size-4">
												<label for="size-4">
													<span class="radio">L</span> </label>
											</div>
											<div class="size-box" data-toggle="tooltip" data-placement="top" title="+ €3,50">
												<input type="radio" name="size" id="size-5">
												<label for="size-5">
													<span class="radio">XL</span> </label>
											</div>
											<div class="size-box" data-toggle="tooltip" data-placement="top" title="+ €2,50">
												<input type="radio" name="size" id="size-6">
												<label for="size-6">
													<span class="radio">XXL</span> </label>
											</div>
										</div>
									</div>
								</form>
							</div>
-->
							<div class="row-size">
								<form class="form-horizontal" name="detailProductCtrl.selectorForm">
									<div class="form-group" ng-repeat="option in detailProductCtrl.product.options">
										<tdv-size-selector option="option" options-selected="detailProductCtrl.optionsSelected" get-references="detailProductCtrl.getReferencesFromOptions(options)" ng-if="option.widget_type==='size'"></tdv-size-selector>
										<tdv-color-selector option="option" options-selected="detailProductCtrl.optionsSelected" get-references="detailProductCtrl.getReferencesFromOptions(options)" ng-if="option.widget_type==='color'"></tdv-color-selector>
										<tdv-select-selector option="option" options-selected="detailProductCtrl.optionsSelected" get-references="detailProductCtrl.getReferencesFromOptions(options)" ng-if="option.widget_type==='select'"></tdv-select-selector>
										<!--
										<label class="col-sm-3 control-label product-label"><span class="atr">Material</span></label>
										<div class="col-sm-9">
											<select class="form-control selectpicker product-select" title="Choose material">
												<option></option>
												<option>Wool (+ € 10.95)</option>
												<option>Fabric (+ € 5.95)</option>
											</select>
										</div>
										-->
									</div>
								</form>
							</div>
							<!--
							<div class="row-size">
								<form class="form-horizontal">
									<div class="form-group">
										<label class="col-sm-3 control-label product-label"><span class="atr">Band</span></label>
										<div class="col-sm-9">
											<select class="form-control selectpicker product-select" title="Choose band">
												<option></option>
												<option>Leather (+ € 20.95)</option>
												<option>Silicone (+ € 5.95)</option>
											</select>
										</div>
									</div>
								</form>
							</div>
							<div class="row-size">
								<form class="form-horizontal">
									<div class="form-group">
										<label class="col-sm-3 control-label product-label"><span class="atr">Diameter</span></label>
										<div class="col-sm-9">
											<select class="form-control selectpicker product-select" title="Choose diameter">
												<option></option>
												<option>20 cm (+ € 11.95)</option>
												<option>30 cm (+ € 5.95)</option>
											</select>
										</div>
									</div>
								</form>
							</div>
-->
							<div class="row-size">
								<form class="form-horizontal">
									<div class="form-group">
										<label class="col-sm-3 control-label product-label"><span class="atr">Material</span></label>
										<div class="col-sm-9">
											<div class="atribute-selected">
												Wool
											</div>
										</div>
									</div>
								</form>
							</div>
							<div class="row-size">
								<button type="button" class="btn btn-green btn-add-to-cart"><i class="ion-ios-cart cart-icon-btn"></i> <span>Add to cart</span></button>
							</div>
						</div>
						<div class="product-data">
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
													<span class="tax">is €4.5</span>
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

								<button type="button" class="btn btn-grey btn-hart pull-left">
									<i class="ion-ios-heart"></i>
									<span>Love work</span>
								</button>


								<button type="button" class="btn btn-grey btn-hart pull-right">
									<i class="ion-ios-box"></i>
									<span>Save in a box</span>
								</button>

							</div>
							<div class="row-size">
								<span class="btn-tagline loved pull-left">Loved 342 times</span>
								<span class="btn-tagline saved pull-right">Saved in 1500 boxes</span>
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
					<a href="#description" aria-controls="description" role="tab" data-toggle="tab">Description &amp; User
					reviews</a>
				</li>
				<li role="presentation">
					<a href="#works" aria-controls="works" role="tab" data-toggle="tab">More works by <?= $deviser->getBrandName() ?></a>
				</li>
			</ul>
			<!-- Tab panes -->
			<div class="tab-content product-description-content">
				<div role="tabpanel" class="tab-pane active work-description-wrapper" id="description">
					<div class="work-profile-description-wrapper">
						<div class="col-sm-8 pad-product">
							<div class="title">Work Description</div>
							<p class="description">
								<?= $product->description ?>
							</p>
						</div>
						<div class="col-sm-4 pad-product">
							<div class="created-text">Created by</div>
							<div class="avatar-wrapper">
								<div class="avatar">
									<a href="<?= Url::to([" deviser/store ", "slug " => $deviser->slug, 'deviser_id' => $deviser->short_id]) ?>">
									<img class="cover" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getAvatarImage())->resize(128, 128) ?>" data-pin-nopin="true">
									<span><?= $deviser->getBrandName() ?></span>
								</a>
								</div>
							</div>
						</div>
					</div>
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
                                    <i class="ion-ios-star-outline"></i>
                                </span>
							<span class="number-score">(20)</span>
							<div class="by-stars"><span>5 stars</span> <span class="number-score">(15)</span></div>
							<div class="by-stars"><span>4 stars</span> <span class="number-score">(5)</span></div>
						</div>exit
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
								<div class="comment-text">Vivamus ultricies mauris mi, nec imperdiet quam facilisis eget.
								</div>
								<div class="replay">
									<span>Reply</span>
									<span class="score">
                                            <i class="ion-ios-star"></i>
                                            <i class="ion-ios-star"></i>
                                            <i class="ion-ios-star"></i>
                                            <i class="ion-ios-star-outline"></i>
                                            <i class="ion-ios-star-outline"></i>
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
                                            <i class="ion-ios-star-outline"></i>
                                            <i class="ion-ios-star-outline"></i>
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
                                            <i class="ion-ios-star-outline"></i>
                                            <i class="ion-ios-star-outline"></i>
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
					<div class="mesonry-row">
						<?php foreach ($deviserProducts as $i => $product) { ?>
							<div class="menu-category list-group">
								<a href="<?= Url::to([" product/detail ", "slug " => $product->slug, 'product_id' => $product->short_id])?>">
									<div class="grid">
										<figure class="effect-zoe">
											<img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage())->resize(400, 0) ?>">
											<figcaption>
												<p class="instauser">
													<?= $product->name ?>
												</p>
												<p class="price">€
													<?= $product->getMinimumPrice()?>
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