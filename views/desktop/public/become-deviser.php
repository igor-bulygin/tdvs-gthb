<?php

use app\assets\desktop\pub\PublicCommonAsset;
use app\models\Person;

PublicCommonAsset::register($this);

/** @var Person $deviser */

$this->title = Yii::t('app/public', 'Become a deviser - Todevise');

?>

	<div class="become-deviser-wrapper" ng-controller="becomeDeviserCtrl as becomeDeviserCtrl">
		<div>
			<div class="container-fluid become-deviser-container">
				<div class="become-deviser-cover">
				<div class="row">
					<div class="col-sm-6"></div>
					<div class="col-sm-6 col-xs-12">
						<div class="call-to-action-wrapper">
							<div class="call-to-action">
								<div class="logo">
									<img src="imgs/logo_white.png">
								</div>
								<div class="tagline" translate="EXPRESS"></div>
								<div class="tagline" translate="YOURSELF"></div>
								<button class="btn btn-white btn-request" ng-click="becomeDeviserCtrl.scrollToForm()" translate="REQUEST_INVITATION"></button>
							</div>
						</div>
					</div>
				</div>
				<div class="row bca-row">
					<div class="col-sm-4 pad-product">
						<div class="bca-content">
							<img class="clock" src="imgs/clock.svg">
							<div class="title" translate="SET_UP_STORE"></div>
							<p translate="OPENING_STORE"></p>
						</div>
					</div>
					<div class="col-sm-4 pad-product">
						<div class="bca-content">
							<img src="imgs/map.svg">
							<div class="title" translate="JOIN_COMMUNITY"></div>
							<p translate="BECOME_PART"></p>
						</div>
					</div>
					<div class="col-sm-4 pad-product">
						<div class="bca-content no-border">
							<img src="imgs/laptop.svg">
							<div class="title" translate="SELLER_TOOLS"></div>
							<p translate="ADVANCED_ANALYTICS"></p>
						</div>
					</div>
				</div>
				<div class="row bca-row second">
					<div class="col-sm-6 pad-product">
						<div class="bca-content no-border">
							<img src="imgs/affiliate.svg">
							<div class="title" translate="AFFILIATE_PROGRAM"></div>
							<p translate="GROW_SALES"></p>
						</div>
					</div>
					<div class="col-sm-6 pad-product">
						<div class="bca-content no-border">
							<span class="big-euro" translate="CURRENCY"></span>
							<div class="title" translate="PRICE"></div>
							<p translate="ENJOY_FEATURES"></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6"></div>
					<div class="col-sm-6">
						<div class="third-block-text">
							<div class="title" translate="GROW_BRAND"></div>
							<p translate="INTERNATIONAL_CUSTOMERS"></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="quotation" translate="OUTLET_STORE"></div>
					</div>
				</div>
				</div>
			</div>
			<div class="request-invitation-wrapper" id="form">
					<div class="title" translate="REQUEST_INVITATION"></div>
					<div class="tagline" translate="STRIVE_EXCELLENCE"></div>
					<div class="request-invitation-container black-form">
						<form name="becomeDeviserCtrl.form" novalidate>
							<div class="title"><span translate="PERSONAL_INFO"></span></div>
							<div>
								<div class="row">
									<div class="col-sm-6">
										<label translate="NAME"></label>
										<input name="name" type="text" class="form-control grey-input ng-class:{'error-input': becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.name)}" required ng-model="becomeDeviserCtrl.invitation.representative_name">
										<form-errors field="becomeDeviserCtrl.form.name" condition="becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.name)"></form-errors>
									</div>
									<div class="col-sm-6">
										<label translate="BRAND_NAME"></label>
										<input name="brand_name" type="text" class="form-control grey-input ng-class:{'error-input': becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.brand_name)}" ng-model="becomeDeviserCtrl.invitation.brand_name">
										<span class="optional-input" translate="OPTIONAL"></span>
										<form-errors field="becomeDeviserCtrl.form.brand_name" condition="becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.brand_name)"></form-errors>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<label translate="EMAIL"></label>
										<input name="email" type="email" class="form-control grey-input ng-class:{'error-input': becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.email)}" required ng-model="becomeDeviserCtrl.invitation.email">
										<form-errors field="becomeDeviserCtrl.form.email" condition="becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.email)"></form-errors>
									</div>
									<div class="col-sm-6">
										<label translate="PHONE"></label>
										<input name="phone_number" type="text" class="form-control grey-input ng-class:{'error-input': becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.phone_number)}" ng-model="becomeDeviserCtrl.invitation.phone_number">
										<span class="optional-input" translate="OPTIONAL"></span>
										<form-errors field="becomeDeviserCtrl.form.phone_number" condition="becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.phone_number)"></form-errors>
									</div>
								</div>
							</div>
							<div class="title"><span translate="YOUR_WORK"></span></div>
							<div class="row">
								<div class="col-sm-12">
									<label translate="WHAT_CREATE"></label>
									<input name="creations_description" type="text" class="form-control grey-input ng-class:{'error-input': becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.creations_description)}" required ng-model="becomeDeviserCtrl.invitation.creations_description">
									<form-errors field="becomeDeviserCtrl.form.creations_description" condition="becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.creations_description)"></form-errors>
								</div>
								<div class="col-sm-12">
									<label translate="LINK_PORTFOLIO"></label>
									<div class="add-portfolio-input" ng-repeat="url in becomeDeviserCtrl.invitation.urls_portfolio track by $index">
										<input name="{{'portfolio_' + $index}}" type="text" class="form-control grey-input ng-class:{'error-input': becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form['portfolio_'+$index])}" required ng-model="becomeDeviserCtrl.invitation.urls_portfolio[$index]" ng-pattern="becomeDeviserCtrl.urlRegEx">
										<span class="ion-close close-add-portfolio" ng-click="becomeDeviserCtrl.splicePortfolio($index)" ng-if="becomeDeviserCtrl.invitation.urls_portfolio.length > 1" ng-cloak></span>
										<form-errors field="becomeDeviserCtrl.form['portfolio_'+$index]" condition="becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form['portfolio_'+$index])"></form-errors>
									</div>
								</div>
								<a href="" class="add-new" ng-click="becomeDeviserCtrl.addUrlPortfolio()" translate="ADD_NEW"></a>
								<div class="col-sm-12">
									<label translate="LINK_VIDEO"></label>
									<div ng-repeat="url in becomeDeviserCtrl.invitation.urls_video track by $index">
									<input name="{{'video_' + $index}}" type="text" class="form-control grey-input ng-class:{'error-input': becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form['video_'+$index])}" ng-model="becomeDeviserCtrl.invitation.urls_video[$index]" ng-pattern="becomeDeviserCtrl.urlRegEx">
									<span class="optional-input" translate="OPTIONAL"></span>
									<span class="glyphicon glyphicon-remove" style="background-color: #c7c7c7; border-radius: 10px; min-width: 10px; min-height: 10px;" ng-click="becomeDeviserCtrl.spliceVideos($index)" ng-if="becomeDeviserCtrl.invitation.urls_video.length > 1" ng-cloak></span>
									<form-errors field="becomeDeviserCtrl.form['video_'+$index]" condition="becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form['video_'+$index])"></form-errors>
									</div>
								</div>
								<a href="" class="add-new" ng-click="becomeDeviserCtrl.addUrlVideo()" translate="ADD_NEW"></a>
								<div class="col-sm-12">
									<label translate="OBSERVATIONS"></label>
									<input name="observations" type="text" class="form-control grey-input ng-class:{'error-input': becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.observations)}" ng-model="becomeDeviserCtrl.invitation.observations">
									<span class="optional-input" translate="OPTIONAL"></span>
									<form-errors field="becomeDeviserCtrl.form.observations" condition="becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.observations)"></form-errors>
								</div>
							</div>
							<button class="btn-red send-btn" ng-click="becomeDeviserCtrl.submitForm(becomeDeviserCtrl.form)" ng-if="!becomeDeviserCtrl.success" ng-cloak>
								<i class="ion-android-navigate"></i>
							</button>
							<div class="ok-sent-mesg-wrapper" ng-if="becomeDeviserCtrl.success" ng-cloak>
								<span class="glyphicon glyphicon-ok ok-icon-rounded"></span>
								<p translate="SUCCESSFULLY_SENT">
									<br translate="WILL_CONTACT"></p>
							</div>
						</form>
					</div>
				</div>
		</div>
	</div>