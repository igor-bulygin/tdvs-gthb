<?php

use app\models\Category;
use yii\helpers\Url;

/** @var Category $category */

// use params to share data between views :(
//$footerMode = array_key_exists('footer_mode', $this->params) ? $this->params['footer_mode'] : 'expanded';

// Footer always collapsed (https://app.asana.com/0/155933916527513/182418486473821) (2016-09-16)
$footerMode = 'collapsed';

?>

<!--GO-TO_FOOTER LINK-->
<button onclick="GoToFooter()" id="go_to_footer"><i class="ion-ios-arrow-down arrow"></i></button>
<script>
	window.onscroll = function() {scrollFunction()};

	function scrollFunction(){
		if (document.body.scrollTop > 30 || document.documentElement.scrollTop > 30) {
			document.getElementById("go_to_footer").style.display = "block";
		} else {
			document.getElementById("go_to_footer").style.display = "none";
		}
	}
	
	function GoToFooter(){
		document.getElementById("go_to_footer").style.display = "none";
		document.body.scrollTop = document.body.scrollHeight;
		document.documentElement.scrollTop = document.documentElement.scrollHeight;
	}
</script>
<!--/GO_TO_FOOTER LINK-->

<!-- FOOTER -->
<footer class="<?= ($footerMode=='expanded') ? 'untoggled' : '' ?>" id="main_footer" name="main_footer" ng-controller="footerCtrl as footerCtrl">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<div class="title">Help &amp; Contact</div>
				<ul class="footer-items mt-10">
					<li>
						<span>-</span>
					</li>
					<li>
						<a href="<?= Url::to(["public/contact"]) ?>"><span translate="footer.CONTACT_US"></span></a>
					</li>
					<?php /*
					<li>
						<a href="#"><span translate="footer.FAQS"></span></a>
					</li>
 					*/ ?>
					<li>
						<span>-</span>
					</li>
					<li>
						<a href="<?= Url::to(["public/about-us"]) ?>"><span translate="footer.ABOUT_US"></span></a>
					</li>
				</ul>
			</div>
			<div class="col-sm-6">
				<div class="title text-center"><span translate="footer.BECOME_DEVISER_QUESTION"></span></div>
				<a href="<?= Url::to(["public/become-deviser"]) ?>" class="btn btn-medium btn-red mt-10 auto-center"><span translate="footer.BECOME_DEVISER"></span></a>
				<div class="title text-center mt-40"><span translate="footer.BECOME_INFLUENCER_QUESTION"></span></div>
				<a href="<?= Url::to(["public/become-influencer"]) ?>" class="btn btn-medium btn-transparent mt-10 auto-center"><span translate="footer.BECOME_INFLUENCER"></span></a>
			</div>
			<div class="col-sm-3">
				<div class="title"><span translate="footer.SUBSCRIBE_NEWSLETTER"></span></div>
				<form name="footerCtrl.newsletterForm" ng-if="!footerCtrl.subscribed" ng-cloak>
					<div class="input-group input-newsletter mt-30">
						<input type="email" class="form-control" name="email" ng-model="footerCtrl.newsletterEmail" placeholder="E-mail">
						<span class="input-group-btn">
							<button class="btn-red send-btn-sm" type="button" ng-click="footerCtrl.sendNewsletter(footerCtrl.newsletterForm)">
								<img src="/imgs/plane.svg" data-pin-nopin="true">
							</button>
						</span>
					</div>
				</form>
				<div ng-if="footerCtrl.subscribed" ng-cloak>
					<p><span translate="footer.SUBSCRIBED_MESSAGE"></span></p>
				</div>
				<div class="title mt-40"><span translate="footer.STAY_CONNECTED"></span></div>
				<ul class="social-items mt-10">
					<li>
						<a href="https://www.facebook.com/todevise" target="_blank">
							<i class="fa fa-facebook" aria-hidden="true"></i>
						</a>
					</li>
					<li>
						<a class="twitter" href="#" target="_blank">
							<i class="fa fa-twitter" aria-hidden="true"></i>
						</a>
					</li>
					<li>
						<a class="google-plus" href="#" target="_blank">
							<i class="fa fa-google-plus" aria-hidden="true"></i>
						</a>
					</li>
					<li>
						<a href="#" target="_blank">
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
						<a href="<?=Url::to(['/public/terms'])?>"><span translate="footer.TERMS_CONDITIONS"></span></a>
					</li>
					<li>·</li>
					<li>
						<a href="<?=Url::to(['/public/privacy'])?>"><span translate="footer.PRIVACY"></span></a>
					</li>
					<li>·</li>
					<li>
						<a href="<?=Url::to(['/public/cookies'])?>"><span translate="footer.COOKIES"></span></a>
					</li>
				</ul>
			</div>
			<div>&copy; <span class="hightlighted">2017 Todevise</span> all rights reserved</div>
		</div>
	</div>
</footer>
<!-- END FOOTER -->
