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
				<div class="title text-center mb-20"><span translate="footer.BECOME_DEVISER_QUESTION"></span></div>
				<a href="<?= Url::to(["public/become-deviser"]) ?>" class="btn btn-medium btn-red auto-center"><span translate="footer.BECOME_DEVISER"></span></a>
				<?php /*
 				<div class="title text-center mt-40 mb-20"><span translate="footer.BECOME_INFLUENCER_QUESTION"></span></div>
				<a href="<?= Url::to(["public/become-influencer"]) ?>" class="btn btn-medium btn-transparent auto-center"><span translate="footer.BECOME_INFLUENCER"></span></a>
 				*/ ?>
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
				<ul class="social-items mt-10 home">
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
<<<<<<< HEAD
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
						<a href="https://www.facebook.com/todevise" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a>
					</li>
					<li>
						<a class="twitter" href="https://twitter.com/todevise" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
					</li>
=======
						<a class="twitter" href="https://twitter.com/todevise" target="_blank">
							<i class="fa fa-twitter" aria-hidden="true"></i>
						</a>
					</li>
					<?php /*
>>>>>>> 395f86ccfba30445560f23294c39fdce872f18a5
					<li>
						<a class="instagram" href="https://www.instagram.com/todevise.official" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a>
					</li-->
					<!--li>
						<a class="google-plus" href="#" target="_blank">
							<i class="fa fa-google-plus" aria-hidden="true"></i>
						</a>
					</li>
					<li>
						<a href="#" target="_blank">
							<i class="fa fa-pinterest-p" aria-hidden="true"></i>
						</a>
<<<<<<< HEAD
					</li-->
=======
					</li>
 					*/ ?>
>>>>>>> 395f86ccfba30445560f23294c39fdce872f18a5
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
