<?php

use app\assets\desktop\pub\PublicCommonAsset;

PublicCommonAsset::register($this);

$this->title = Yii::t('app/public', 'BECOME_A_DEVISER');

?>

	<div class="become-deviser-wrapper" ng-controller="becomeDeviserCtrl as becomeDeviserCtrl">
		<div>
			<div class="container-fluid become-deviser-container">
				<div class="become-deviser-cover">
				<div class="container">
				<div class="header row">
					<div class="col-sm-6"></div>
					<div class="col-sm-6 col-xs-12">
						<div class="call-to-action-wrapper">
							<div class="call-to-action">
								<div class="logo">
									<img src="imgs/logo_white.png">
								</div>
								<div class="tagline" translate="todevise.become_deviser.EXPRESS"></div>
								<div class="tagline" translate="todevise.become_deviser.YOURSELF"></div>
								<button class="btn btn-red btn-big btn-red" ng-click="becomeDeviserCtrl.scrollToForm()" translate="todevise.become_deviser.REQUEST_INVITATION"></button>
							</div>
						</div>
					</div>
				</div>
				<div class="row bca-row">
					<div class="col-sm-4 pad-product">
						<div class="bca-content">
							<img class="clock" src="imgs/clock.svg">
							<div class="title" translate="todevise.become_deviser.SET_UP_STORE"></div>
							<p translate="todevise.become_deviser.OPENING_STORE"></p>
						</div>
					</div>
					<div class="col-sm-4 pad-product">
						<div class="bca-content">
							<img src="imgs/map.svg">
							<div class="title" translate="todevise.become_deviser.JOIN_COMMUNITY"></div>
							<p translate="todevise.become_deviser.BECOME_PART"></p>
						</div>
					</div>
					<div class="col-sm-4 pad-product">
						<div class="bca-content no-border">
							<img src="imgs/laptop.svg">
							<div class="title" translate="todevise.become_deviser.SELLER_TOOLS"></div>
							<p translate="todevise.become_deviser.ADVANCED_ANALYTICS"></p>
						</div>
					</div>
				</div>
				<div class="row bca-row second">
					<div class="col-sm-6 pad-product">
						<div class="bca-content no-border">
							<img src="imgs/affiliate.svg">
							<div class="title" translate="todevise.become_deviser.AFFILIATE_PROGRAM"></div>
							<p translate="todevise.become_deviser.GROW_SALES"></p>
						</div>
					</div>
					<div class="col-sm-6 pad-product">
						<div class="bca-content no-border">
							<span class="big-euro" translate="todevise.become_deviser.CURRENCY"></span>
							<div class="title" translate="global.PRICE"></div>
							<p translate="todevise.become_deviser.ENJOY_FEATURES"></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6"></div>
					<div class="col-sm-6">
						<div class="third-block-text">
							<div class="title" translate="todevise.become_deviser.GROW_BRAND"></div>
							<p translate="todevise.become_deviser.INTERNATIONAL_CUSTOMERS"></p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="quotation" translate="todevise.become_deviser.OUTLET_STORE"></div>
					</div>
				</div>
				</div>
				</div>
			</div>
			<div class="request-invitation-wrapper" id="form">
					<div class="title" translate="todevise.become_deviser.REQUEST_INVITATION"></div>
					<div class="tagline" translate="todevise.become_deviser.STRIVE_EXCELLENCE"></div>
					<div class="request-invitation-container black-form">
						<form name="becomeDeviserCtrl.form" novalidate>
							<div class="title"><span translate="todevise.become_deviser.PERSONAL_INFO"></span></div>
							<div>
								<div class="row">
									<div class="col-sm-6">
										<label translate="todevise.become_deviser.NAME"></label>
										<input name="name" type="text" class="form-control grey-input ng-class:{'error-input': becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.name)}" required ng-model="becomeDeviserCtrl.invitation.representative_name">
										<form-errors field="becomeDeviserCtrl.form.name" condition="becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.name)"></form-errors>
									</div>
									<div class="col-sm-6">
										<label translate="global.user.BRAND_NAME"></label>
										<input name="brand_name" type="text" class="form-control grey-input ng-class:{'error-input': becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.brand_name)}" ng-model="becomeDeviserCtrl.invitation.brand_name">
										<span class="optional-input" translate="global.OPTIONAL"></span>
										<form-errors field="becomeDeviserCtrl.form.brand_name" condition="becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.brand_name)"></form-errors>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<label translate="global.user.EMAIL"></label>
										<input name="email" type="email" class="form-control grey-input ng-class:{'error-input': becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.email)}" required ng-model="becomeDeviserCtrl.invitation.email">
										<form-errors field="becomeDeviserCtrl.form.email" condition="becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.email)"></form-errors>
									</div>
									<div class="col-sm-6">
										<label translate="global.user.PHONE"></label>
										<input name="phone_number" type="text" class="form-control grey-input ng-class:{'error-input': becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.phone_number)}" ng-model="becomeDeviserCtrl.invitation.phone_number">
										<span class="optional-input" translate="global.OPTIONAL"></span>
										<form-errors field="becomeDeviserCtrl.form.phone_number" condition="becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.phone_number)"></form-errors>
									</div>
								</div>
							</div>
							<div class="title"><span translate="todevise.become_deviser.YOUR_WORK"></span></div>
							<div class="row">
								<div class="col-sm-12">
									<label translate="todevise.become_deviser.WHAT_CREATE"></label>
									<input name="creations_description" type="text" class="form-control grey-input ng-class:{'error-input': becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.creations_description)}" required ng-model="becomeDeviserCtrl.invitation.creations_description">
									<form-errors field="becomeDeviserCtrl.form.creations_description" condition="becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.creations_description)"></form-errors>
								</div>
								<div class="col-sm-12">
									<label translate="todevise.become_deviser.LINK_PORTFOLIO"></label>
									<div class="add-portfolio-input" ng-repeat="url in becomeDeviserCtrl.invitation.urls_portfolio track by $index">
										<input name="{{'portfolio_' + $index}}" type="text" class="form-control grey-input ng-class:{'error-input': becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form['portfolio_'+$index])}" required ng-model="becomeDeviserCtrl.invitation.urls_portfolio[$index]" ng-pattern="becomeDeviserCtrl.urlRegEx">
										<span class="ion-close close-add-portfolio" ng-click="becomeDeviserCtrl.splicePortfolio($index)" ng-if="becomeDeviserCtrl.invitation.urls_portfolio.length > 1" ng-cloak></span>
										<form-errors field="becomeDeviserCtrl.form['portfolio_'+$index]" condition="becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form['portfolio_'+$index])"></form-errors>
									</div>
								</div>
								<a href="" class="add-new" ng-click="becomeDeviserCtrl.addUrlPortfolio()" translate="todevise.become_deviser.ADD_NEW"></a>
								<div class="col-sm-12">
									<label translate="todevise.become_deviser.LINK_VIDEO"></label>
									<div ng-repeat="url in becomeDeviserCtrl.invitation.urls_video track by $index">
									<input name="{{'video_' + $index}}" type="text" class="form-control grey-input ng-class:{'error-input': becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form['video_'+$index])}" ng-model="becomeDeviserCtrl.invitation.urls_video[$index]" ng-pattern="becomeDeviserCtrl.urlRegEx">
									<span class="optional-input" translate="global.OPTIONAL"></span>
									<span class="glyphicon glyphicon-remove" style="background-color: #c7c7c7; border-radius: 10px; min-width: 10px; min-height: 10px;" ng-click="becomeDeviserCtrl.spliceVideos($index)" ng-if="becomeDeviserCtrl.invitation.urls_video.length > 1" ng-cloak></span>
									<form-errors field="becomeDeviserCtrl.form['video_'+$index]" condition="becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form['video_'+$index])"></form-errors>
									</div>
								</div>
								<a href="" class="add-new" ng-click="becomeDeviserCtrl.addUrlVideo()" translate="todevise.become_deviser.ADD_NEW"></a>
								<div class="col-sm-12">
									<label translate="todevise.become_deviser.OBSERVATIONS"></label>
									<input name="observations" type="text" class="form-control grey-input ng-class:{'error-input': becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.observations)}" ng-model="becomeDeviserCtrl.invitation.observations">
									<span class="optional-input" translate="global.OPTIONAL"></span>
									<form-errors field="becomeDeviserCtrl.form.observations" condition="becomeDeviserCtrl.has_error(becomeDeviserCtrl.form, becomeDeviserCtrl.form.observations)"></form-errors>
								</div>
							</div>
							<button class="btn-red send-btn" ng-click="becomeDeviserCtrl.submitForm(becomeDeviserCtrl.form)" ng-if="!becomeDeviserCtrl.success" ng-cloak>
								<i class="ion-android-navigate"></i>
							</button>
							<div class="ok-sent-mesg-wrapper" ng-if="becomeDeviserCtrl.success" ng-cloak>
								<span class="glyphicon glyphicon-ok ok-icon-rounded"></span>
								<p translate="todevise.become_deviser.SUCCESSFULLY_SENT">
									<br translate="todevise.become_deviser.WILL_CONTACT"></p>
							</div>
						</form>
					</div>
				</div>
		</div>
	</div>