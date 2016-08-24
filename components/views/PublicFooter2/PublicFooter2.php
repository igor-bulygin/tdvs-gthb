<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>

<!-- FOOTER -->
<footer>
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<div class="title">Categories</div>
				<ul class="footer-items">
					<li>
						<a href="<?= Url::to(["public/category-b", "slug" => 'art', 'category_id' => '1a23b'])?>">Art</a>
					</li>
					<li>
						<a href="<?= Url::to(["public/category-b", "slug" => 'fashion', 'category_id' => '4a2b4'])?>">Fashion</a>
					</li>
					<li>
						<a href="<?= Url::to(["public/category-b", "slug" => 'industrial-design', 'category_id' => '2p45q'])?>">Industrial design</a>
					</li>
					<li>
						<a href="<?= Url::to(["public/category-b", "slug" => 'jewelry', 'category_id' => '3f78g'])?>">Jewelry</a>
					</li>
					<li>
						<a href="#">Other</a>
					</li>
				</ul>
			</div>
			<div class="col-sm-3">
				<div class="title">Help &amp; Contact</div>
				<ul class="footer-items">
					<li>
						<a href="#">Contact us</a>
					</li>
					<li>
						<a href="#">Returns &amp; Warranties</a>
					</li>
					<li>
						<a href="#">About us</a>
					</li>
				</ul>
			</div>
			<div class="col-sm-3">
				<div class="title">Join Todevise</div>
				<button type="button" class="btn red-btn">Become a deviser</button>
			</div>
			<div class="col-sm-3">
				<div class="title">Stay connected</div>
				<div class="footer-text">Subscribe to our newsletter</div>
				<div class="input-group input-newsletter">
					<input type="text" class="form-control" placeholder="Email">
					<span class="input-group-btn">
                                <button class="btn btn-default btn-send" type="button">
                                    <span class="ion-forward"></span>
                                </button>
                            </span>
				</div>
				<div class="footer-text social">
					Follow us
				</div>
				<ul class="social-items">
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
		<div class="copyright">
			<div>Todevise Copyright 2016</div>
			<div>
				<ul>
					<li>
						<a href="#">Terms &amp; Conditions</a>
					</li>
					<li>|</li>
					<li>
						<a href="#">Privacy policy</a>
					</li>
					<li>|</li>
					<li>
						<a href="#">Cookies policy</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</footer>