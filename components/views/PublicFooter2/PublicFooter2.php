<?php

use app\models\Category;
use yii\helpers\Url;

/** @var Category $category */

// use params to share data between views :(
//$footerMode = array_key_exists('footer_mode', $this->params) ? $this->params['footer_mode'] : 'expanded';

// Footer always collapsed (https://app.asana.com/0/155933916527513/182418486473821) (2016-09-16)
$footerMode = 'collapsed';

?>

<!-- FOOTER -->
<footer class="<?= ($footerMode=='expanded') ? 'untoggled' : '' ?>">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<div class="title">Help &amp; Contact</div>
				<ul class="footer-items mt-10">
					<li>
						<span>-</span>
					</li>
					<li>
						<a href="#">Contact us</a>
					</li>
					<li>
						<a href="#">FAQs</a>
					</li>
					<li>
						<span>-</span>
					</li>
					<li>
						<a href="<?= Url::to(["public/about-us"]) ?>">ABOUT US</a>
					</li>
				</ul>
			</div>
			<div class="col-sm-6">
				<div class="title text-center">Do you want to become a deviser?</div>
				<a href="<?= Url::to(["public/become-deviser"]) ?>" class="btn btn-medium btn-red mt-10 auto-center">Become a deviser</a>
				<div class="title text-center mt-40">Do you want to become an influencer?</div>
				<a href="#" class="btn btn-medium btn-transparent mt-10 auto-center">Become a influencer</a>
			</div>
			<div class="col-sm-3">
				<div class="title">Subscribe to our newsletter</div>
				<div class="input-group input-newsletter mt-30">
					<input type="text" class="form-control" placeholder="E-mail">
					<span class="input-group-btn">
						<button class="btn-red send-btn-sm" type="button">
							<img src="/imgs/plane.svg" data-pin-nopin="true">
						</button>
					</span>
				</div>
				<div class="title mt-40">Stay connected</div>
				<ul class="social-items mt-10">
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
		<div class="copyright mt-40">
			<div>
				<ul>
					<li>
						<a href="#">Terms &amp; Conditions</a>
					</li>
					<li>·</li>
					<li>
						<a href="#">Privacy policy</a>
					</li>
					<li>·</li>
					<li>
						<a href="#">Cookies policy</a>
					</li>
				</ul>
			</div>
			<div>&copy; <span class="hightlighted">2017 Todevise</span> all rights reserved</div>
		</div>
	</div>
</footer>
<!-- END FOOTER -->
