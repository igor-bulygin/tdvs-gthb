<?php
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
use app\assets\desktop\pub\IndexAsset;
use app\assets\desktop\pub\Index2Asset;

Index2Asset::register($this);

$this->title = 'Todevise / Home';

/** @var Person $deviser */
/** @var Product $product */

?>

<div class="banner-deviser">
	<div class="container pad-about">
		<img class="cover" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getHeaderBackgroundImage())->resize(1280, 450) ?>">
		<div class="banner-deviser-content">
			<div class="grey-overlay"></div>
			<div class="container">
				<div class="deviser-profile">
					<div class="avatar">
						<img class="cover" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($deviser->getAvatarImage())->resize(250, 250) ?>">
					</div>
					<div class="deviser-data">
						<div class="name">
							<?= $deviser->getBrandName() ?>
						</div>
						<div class="location">
							<?= $deviser->getCityLabel() ?>
						</div>
						<div class="description">
							<?= $deviser->getShortDescription() ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="store">
	<div class="container">
		<div class="row">
			<div class="col-md-2">
				<nav class="menu-store" data-spy="affix" data-offset-top="450">
					<ul class="mt-0">
						<li>
							<a class="active" href="#">Store</a>
							<ul class="submenu-store">
								<li class="mt10">
									<a href="#">Ceramics</a>
								</li>
								<li>
									<a href="#">Sofas</a>
								</li>
								<li class="mb20">
									<a href="#">Paintings</a>
								</li>
							</ul>
						</li>
					</ul>
					<ul>
						<li>
							<a href="<?= Url::to(["deviser/about", "slug" => $deviser->slug, 'deviser_id' => $deviser->short_id])?>">About <?= $deviser->getName() ?></a>
						</li>
						<li>
							<a href="#">Press</a>
						</li>
						<li>
							<a href="#">Videos</a>
						</li>
						<li>
							<a href="#">FAQ</a>
						</li>
					</ul>
				</nav>
			</div>
			<div class="col-md-10">
				<div class="content-store">
					<div class="cathegory-wrapper">
						<div class="col-md-3 col-sm-3 col-xs-3 pad-cathegory">
							<a href="#">
								<figure class="cathegory">
									<img src="/imgs/deviser-cathegory-2.jpg">
									<figcaption>
                                                <span class="name">
                                                    Sport
                                                </span>
									</figcaption>
								</figure>
							</a>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3 pad-cathegory">
							<a href="#">
								<figure class="cathegory">
									<img class="active" src="/imgs/deviser-cathegory-2.jpg">
									<figcaption>
                                                <span class="name">
                                                    Menswear
                                                </span>
									</figcaption>
								</figure>
							</a>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3 pad-cathegory">
							<a href="#">
								<figure class="cathegory">
									<img src="/imgs/deviser-cathegory-2.jpg">
									<figcaption>
                                                <span class="name">
                                                    Jewlery
                                                </span>
									</figcaption>
								</figure>
							</a>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3 pad-cathegory">
							<a href="#">
								<figure class="cathegory">
									<img src="/imgs/deviser-cathegory-2.jpg">
									<figcaption>
                                                <span class="name">
                                                    Trausers
                                                </span>
									</figcaption>
								</figure>
							</a>
						</div>
					</div>
					<div class="store-grid">
						<div class="title-wrapper">
							<span class="title">Menswear</span>
						</div>
						<nav class="products-menu">
							<ul>
								<li>
									<a class="active" href="#">Pants</a>
								</li>
								<li>
									<a href="#">Socks</a>
								</li>
								<li>
									<a href="#">Belts</a>
								</li>
							</ul>
						</nav>
						<div class="mesonry-row">
							<?php foreach ($products as $i => $product) { ?>
								<div class="menu-category list-group">
									<a href="<?= Url::to(["public/product-b", "slug" => Utils::l($product->slug), 'product_id' => $product->short_id])?>">
										<div class="grid">
											<figure class="effect-zoe">
												<img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($product->getMainImage()) ?>">
												<figcaption>
													<p class="instauser">
														<?= Utils::l($product->name) ?>
													</p>
													<p class="price">€ <?= $product->getMinimumPrice() ?></p>
												</figcaption>
											</figure>
										</div>
									</a>
								</div>
							<?php } ?>
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-1.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-2.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-3.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-4.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-5.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-6.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-7.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-1.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-2.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-3.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-4.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-5.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-6.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-7.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-1.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-2.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-3.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-4.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-5.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-6.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-7.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-1.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-2.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-3.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-4.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-5.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-6.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
<!--							<div class="menu-category list-group">-->
<!--								<a href="#">-->
<!--									<div class="grid">-->
<!--										<figure class="effect-zoe">-->
<!--											<img class="grid-image" src="/imgs/photo-grid-v-7.jpg">-->
<!--											<figcaption>-->
<!--												<p class="instauser">-->
<!--													Black dress-->
<!--												</p>-->
<!--												<p class="price">€ 3.230</p>-->
<!--											</figcaption>-->
<!--										</figure>-->
<!--									</div>-->
<!--								</a>-->
<!--							</div>-->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
