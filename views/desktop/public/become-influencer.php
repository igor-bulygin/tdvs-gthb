<?php

use app\assets\desktop\pub\PublicCommonAsset;

PublicCommonAsset::register($this);

$this->title = Yii::t('app/public', 'BECOME_AN_INFLUENCER');

?>

	<div class="become-deviser-wrapper" ng-controller="becomeInfluencerCtrl as becomeInfluencerCtrl">
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
								<div class="tagline" translate="todevise.become_influencer.EXPRESS"></div>
								<div class="tagline" translate="todevise.become_influencer.YOURSELF"></div>
								<button class="btn btn-white btn-request" ng-click="becomeInfluencerCtrl.scrollToForm()" translate="todevise.become_influencer.REQUEST_INVITATION"></button>
							</div>
						</div>
					</div>
				</div>
				<div class="row bca-row">
					<div class="col-sm-4 pad-product">
						<div class="bca-content">
							<img class="clock" src="imgs/clock.svg">
							<div class="title" translate="todevise.become_influencer.SET_UP_STORE"></div>
							<p translate="todevise.become_influencer.OPENING_STORE"></p>
						</div>
					</div>
					<div class="col-sm-4 pad-product">
						<div class="bca-content">
							<img src="imgs/map.svg">
							<div class="title" translate="todevise.become_influencer.JOIN_COMMUNITY"></div>
							<p translate="todevise.become_influencer.BECOME_PART"></p>
						</div>
					</div>
					<div class="col-sm-4 pad-product">
						<div class="bca-content no-border">
							<img src="imgs/laptop.svg">
							<div class="title" translate="todevise.become_influencer.SELLER_TOOLS"></div>
							<p translate="todevise.become_influencer.ADVANCED_ANALYTICS"></p>
						</div>
					</div>
				</div>
				<div class="row bca-row second">
					<div class="col-sm-6 pad-product">
						<div class="bca-content no-border">
							<img src="imgs/affiliate.svg">
							<div class="title" translate="todevise.become_influencer.AFFILIATE_PROGRAM"></div>
							<p translate="todevise.become_influencer.GROW_SALES"></p>
						</div>
					</div>
					<div class="col-sm-6 pad-product">
						<div class="bca-content no-border">
							<span class="big-euro" translate="todevise.become_influencer.CURRENCY"></span>
							<div class="title" translate="global.PRICE"></div>
							<p translate="todevise.become_influencer.ENJOY_FEATURES"></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6"></div>
					<div class="col-sm-6">
						<div class="third-block-text">
							<div class="title" translate="todevise.become_influencer.GROW_BRAND"></div>
							<p translate="todevise.become_influencer.INTERNATIONAL_CUSTOMERS"></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="quotation" translate="todevise.become_influencer.OUTLET_STORE"></div>
					</div>
				</div>
				</div>
			</div>
			<div class="request-invitation-wrapper" id="form">
					<div class="title" translate="todevise.become_influencer.REQUEST_INVITATION"></div>
					<div class="tagline" translate="todevise.become_influencer.STRIVE_EXCELLENCE"></div>
					<div class="request-invitation-container black-form">
						<form name="becomeInfluencerCtrl.form" novalidate>
							<div class="title"><span translate="todevise.become_influencer.PERSONAL_INFO"></span></div>
							<div>
								<div class="row">
									<div class="col-sm-6">
										<label translate="todevise.become_influencer.NAME"></label>
										<input name="name" type="text" class="form-control grey-input ng-class:{'error-input': becomeInfluencerCtrl.has_error(becomeInfluencerCtrl.form, becomeInfluencerCtrl.form.name)}" required ng-model="becomeInfluencerCtrl.invitation.representative_name">
										<form-errors field="becomeInfluencerCtrl.form.name" condition="becomeInfluencerCtrl.has_error(becomeInfluencerCtrl.form, becomeInfluencerCtrl.form.name)"></form-errors>
									</div>
									<div class="col-sm-6">
										<label translate="global.user.BRAND_NAME"></label>
										<input name="brand_name" type="text" class="form-control grey-input ng-class:{'error-input': becomeInfluencerCtrl.has_error(becomeInfluencerCtrl.form, becomeInfluencerCtrl.form.brand_name)}" ng-model="becomeInfluencerCtrl.invitation.brand_name">
										<span class="optional-input" translate="global.OPTIONAL"></span>
										<form-errors field="becomeInfluencerCtrl.form.brand_name" condition="becomeInfluencerCtrl.has_error(becomeInfluencerCtrl.form, becomeInfluencerCtrl.form.brand_name)"></form-errors>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<label translate="global.user.EMAIL"></label>
										<input name="email" type="email" class="form-control grey-input ng-class:{'error-input': becomeInfluencerCtrl.has_error(becomeInfluencerCtrl.form, becomeInfluencerCtrl.form.email)}" required ng-model="becomeInfluencerCtrl.invitation.email">
										<form-errors field="becomeInfluencerCtrl.form.email" condition="becomeInfluencerCtrl.has_error(becomeInfluencerCtrl.form, becomeInfluencerCtrl.form.email)"></form-errors>
									</div>
									<div class="col-sm-6">
										<label translate="global.user.PHONE"></label>
										<input name="phone_number" type="text" class="form-control grey-input ng-class:{'error-input': becomeInfluencerCtrl.has_error(becomeInfluencerCtrl.form, becomeInfluencerCtrl.form.phone_number)}" ng-model="becomeInfluencerCtrl.invitation.phone_number">
										<span class="optional-input" translate="global.OPTIONAL"></span>
										<form-errors field="becomeInfluencerCtrl.form.phone_number" condition="becomeInfluencerCtrl.has_error(becomeInfluencerCtrl.form, becomeInfluencerCtrl.form.phone_number)"></form-errors>
									</div>
								</div>
							</div>
							<div class="title"><span translate="todevise.become_influencer.YOUR_WORK"></span></div>
							<div class="row">
								<div class="col-sm-12">
									<label translate="todevise.become_influencer.WHAT_CREATE"></label>
									<input name="creations_description" type="text" class="form-control grey-input ng-class:{'error-input': becomeInfluencerCtrl.has_error(becomeInfluencerCtrl.form, becomeInfluencerCtrl.form.creations_description)}" required ng-model="becomeInfluencerCtrl.invitation.creations_description">
									<form-errors field="becomeInfluencerCtrl.form.creations_description" condition="becomeInfluencerCtrl.has_error(becomeInfluencerCtrl.form, becomeInfluencerCtrl.form.creations_description)"></form-errors>
								</div>
								<div class="col-sm-12">
									<label translate="todevise.become_influencer.LINK_PORTFOLIO"></label>
									<div class="add-portfolio-input" ng-repeat="url in becomeInfluencerCtrl.invitation.urls_portfolio track by $index">
										<input name="{{'portfolio_' + $index}}" type="text" class="form-control grey-input ng-class:{'error-input': becomeInfluencerCtrl.has_error(becomeInfluencerCtrl.form, becomeInfluencerCtrl.form['portfolio_'+$index])}" required ng-model="becomeInfluencerCtrl.invitation.urls_portfolio[$index]" ng-pattern="becomeInfluencerCtrl.urlRegEx">
										<span class="ion-close close-add-portfolio" ng-click="becomeInfluencerCtrl.splicePortfolio($index)" ng-if="becomeInfluencerCtrl.invitation.urls_portfolio.length > 1" ng-cloak></span>
										<form-errors field="becomeInfluencerCtrl.form['portfolio_'+$index]" condition="becomeInfluencerCtrl.has_error(becomeInfluencerCtrl.form, becomeInfluencerCtrl.form['portfolio_'+$index])"></form-errors>
									</div>
								</div>
								<a href="" class="add-new" ng-click="becomeInfluencerCtrl.addUrlPortfolio()" translate="todevise.become_influencer.ADD_NEW"></a>
								<div class="col-sm-12">
									<label translate="todevise.become_influencer.LINK_VIDEO"></label>
									<div ng-repeat="url in becomeInfluencerCtrl.invitation.urls_video track by $index">
									<input name="{{'video_' + $index}}" type="text" class="form-control grey-input ng-class:{'error-input': becomeInfluencerCtrl.has_error(becomeInfluencerCtrl.form, becomeInfluencerCtrl.form['video_'+$index])}" ng-model="becomeInfluencerCtrl.invitation.urls_video[$index]" ng-pattern="becomeInfluencerCtrl.urlRegEx">
									<span class="optional-input" translate="global.OPTIONAL"></span>
									<span class="glyphicon glyphicon-remove" style="background-color: #c7c7c7; border-radius: 10px; min-width: 10px; min-height: 10px;" ng-click="becomeInfluencerCtrl.spliceVideos($index)" ng-if="becomeInfluencerCtrl.invitation.urls_video.length > 1" ng-cloak></span>
									<form-errors field="becomeInfluencerCtrl.form['video_'+$index]" condition="becomeInfluencerCtrl.has_error(becomeInfluencerCtrl.form, becomeInfluencerCtrl.form['video_'+$index])"></form-errors>
									</div>
								</div>
								<a href="" class="add-new" ng-click="becomeInfluencerCtrl.addUrlVideo()" translate="todevise.become_influencer.ADD_NEW"></a>
								<div class="col-sm-12">
									<label translate="todevise.become_influencer.OBSERVATIONS"></label>
									<input name="observations" type="text" class="form-control grey-input ng-class:{'error-input': becomeInfluencerCtrl.has_error(becomeInfluencerCtrl.form, becomeInfluencerCtrl.form.observations)}" ng-model="becomeInfluencerCtrl.invitation.observations">
									<span class="optional-input" translate="global.OPTIONAL"></span>
									<form-errors field="becomeInfluencerCtrl.form.observations" condition="becomeInfluencerCtrl.has_error(becomeInfluencerCtrl.form, becomeInfluencerCtrl.form.observations)"></form-errors>
								</div>
							</div>
							<button class="btn-red send-btn" ng-click="becomeInfluencerCtrl.submitForm(becomeInfluencerCtrl.form)" ng-if="!becomeInfluencerCtrl.success" ng-cloak>
								<i class="ion-android-navigate"></i>
							</button>
							<div class="ok-sent-mesg-wrapper" ng-if="becomeInfluencerCtrl.success" ng-cloak>
								<span class="glyphicon glyphicon-ok ok-icon-rounded"></span>
								<p translate="todevise.become_influencer.SUCCESSFULLY_SENT">
									<br translate="todevise.become_influencer.WILL_CONTACT"></p>
							</div>
						</form>
					</div>
				</div>
		</div>
	</div>