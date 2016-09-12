<?php
use app\assets\desktop\pub\PublicCommonAsset;
use app\components\DeviserHeader;
use app\components\DeviserMenu;
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
use app\assets\desktop\pub\Index2Asset;
use app\assets\desktop\pub\BecomeDeviserAsset;

BecomeDeviserAsset::register($this);

/** @var Person $deviser */

$this->title = 'Become a Deviser - Todevise';

?>

	<div class="become-deviser-wrapper">
		<div class="container">
			<div class="become-deviser-cover">
				<div class="row">
					<div class="col-sm-6"></div>
					<div class="col-sm-6 col-xs-12">
						<div class="call-to-action-wrapper">
							<div class="call-to-action">
								<div class="logo">
									<img src="imgs/logo_white.png">
								</div>
								<div class="tagline">
									Express
								</div>
								<div class="tagline">
									Yourself
								</div>
								<button class="btn btn-white btn-request">Request invitation</button>
							</div>
						</div>
					</div>
				</div>
				<div class="row bca-row">
					<div class="col-sm-4 pad-product">
						<div class="bca-content">
							<img class="clock" src="imgs/clock.svg">
							<div class="title">Set up your store in minutes</div>
							<p>Opening your store on Todevise is a fast and efortless process.</p>
						</div>
					</div>
					<div class="col-sm-4 pad-product">
						<div class="bca-content">
							<img src="imgs/map.svg">
							<div class="title">Join a select community of devisers</div>
							<p>Become part of a group of creators who share the same passion for innovation and quality as you, and let your creativity flourish.</p>
						</div>
					</div>
					<div class="col-sm-4 pad-product">
						<div class="bca-content no-border">
							<img src="imgs/laptop.svg">
							<div class="title">Sellers tools</div>
							<p>Get advanced analytics and manage your sales and customer relations, all in one place.</p>
						</div>
					</div>
				</div>
				<div class="row bca-row second">
					<div class="col-sm-6 pad-product">
						<div class="bca-content no-border">
							<img src="imgs/affiliate.svg">
							<div class="title">Affiliate program</div>
							<p>Grow your customer base and sales exponentially with our innovative affiliate program. Promote works and earn funds.</p>
						</div>
					</div>
					<div class="col-sm-6 pad-product">
						<div class="bca-content no-border">
							<span class="big-euro">€</span>
							<div class="title">Price</div>
							<p>Enjoy all the Todevise features for only 99€/year (billed annually) and 4% for each transaction. Marketing and affiliates costs not included.</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6"></div>
					<div class="col-sm-6">
						<div class="third-block-text">
							<div class="title">Grow your brand</div>
							<p>Reach international customers who understand your philosophy and appreciate your work. Get marketing guidance and SEO/SEM optimization by our team of experts. Potential onsite and social media promotion of your work.</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="quotation">Having your outlet store on Todevise opens your business to a world of opportunities. Reach new customers, upload products with ease, manage and track your sales efficiently.</div>
					</div>
				</div>
				<div class="request-invitation-wrapper" ng-controller="becomeDeviserCtrl as becomeDeviserCtrl">
					<div class="title">Request invitation</div>
					<div class="tagline">We constantly strive for excellence, and for this reason an invitation is needed to register a deviser.</div>
					<div class="request-invitation-container black-form">
						<form name="becomeDeviserCtrl.form" novalidate>
							<div class="title"><span>Personal info</span></div>
							<div class="row">
								<div class="col-sm-6">
								    <label>Name</label>
									<input name="name" type="text" class="form-control grey-input" required ng-model="becomeDeviserCtrl.invitation.representative_name">
								</div>
								<div class="col-sm-6">
								    <label>Brand name</label>
									<input name="brand_name" type="text" class="form-control grey-input" ng-model="becomeDeviserCtrl.invitation.brand_name">
								</div>
								<div class="col-sm-6">
								    <label>Email</label>
									<input name="email" type="email" class="form-control grey-input" required ng-model="becomeDeviserCtrl.invitation.email">
								</div>
								<div class="col-sm-6">
								    <label>Phone number</label>
									<input name="phone_number" type="text" class="form-control grey-input" ng-model="becomeDeviserCtrl.invitation.phone_number">
								</div>
							</div>
							<div class="title"><span>Your work</span></div>
							<div class="row">
								<div class="col-sm-12">
								    <label>What do you create?</label>
									<input name="creations_description" type="text" class="form-control grey-input" required ng-model="becomeDeviserCtrl.invitation.creations_description">
								</div>
								<div class="col-sm-12" ng-repeat="url in becomeDeviserCtrl.invitation.urls_portfolio track by $index">
								    <label>Link to portfolio</label>
									<input name="{{'portfolio_' + $index}}" type="url" class="form-control grey-input" required ng-model="becomeDeviserCtrl.invitation.urls_portfolio[$index]">
								</div>
								<a href="" class="add-new" ng-click="becomeDeviserCtrl.addUrlPortfolio()">Add new +</a>
								<div class="col-sm-12" ng-repeat="url in becomeDeviserCtrl.invitation.urls_video track by $index">
								    <label>Link to video</label>
									<input name="{{'video_' + $index}}" type="url" class="form-control grey-input" ng-model="becomeDeviserCtrl.invitation.urls_video[$index]">
								</div>
								<a href="" class="add-new" ng-click="becomeDeviserCtrl.addUrlVideo()">Add new +</a>
								<div class="col-sm-12">
								    <label>Observations</label>
									<input name="observations" type="text" class="form-control grey-input" ng-model="becomeDeviserCtrl.invitation.observations">
								</div>
							</div>
							<button class="btn-red send-btn" ng-click="becomeDeviserCtrl.submitForm(becomeDeviserCtrl.form)">
								<i class="ion-android-navigate"></i>
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>