<?php

use app\helpers\Utils;
use app\models\Category;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var Category $category */

// use params to share data between views :(
$footerMode = array_key_exists('footer_mode', $this->params) ? $this->params['footer_mode'] : 'expanded';

?>

<!-- FOOTER -->
<footer class="<?= ($footerMode=='expanded') ? 'untoggled' : '' ?>">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<div class="title">Categories</div>
				<ul class="footer-items">
					<?php foreach($categories as $category) { ?>
						<li>
							<a href="<?= Url::to(["public/category-b", "slug" => $category->slug, 'category_id' => $category->short_id])?>"><?=Utils::l($category->name)?></a>
						</li>
					<?php } ?>
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
<!-- END FOOTER -->