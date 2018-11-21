<?php

use app\models\Category;
use lajax\languagepicker\widgets\LanguagePicker;
use yii\helpers\Url;

/** @var Category $category */

app\components\assets\PublicHeader2Asset::register($this);

?>

<style>
	.navbar.terciary ul {margin: 12px 0 0 0;}
</style>

<?php $this->registerJs("var _selectedCategoryId = '" . $categoryId."';", \yii\web\View::POS_HEAD); ?>
<?php $this->registerJs("var _searchTypeId = '".$searchTypeId."';", \yii\web\View::POS_HEAD); ?>

<div ng-controller="publicHeaderCtrl as publicHeaderCtrl">
<header>
	<nav class="navbar navbar-default" id="main_header" >
		<div class="container">
			<div class="row align-items-center">
			<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
				<a class="navbar-brand" href="<?= Url::to(["public/index"])?>">
					<svg version="1.1" id="todevise_logo" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
					 viewBox="0 0 39.9 12.2" style="enable-background:new 0 0 39.9 12.2;" xml:space="preserve">
						<path class="img_logo" id="p1" d="M34.1,7.3c-0.3,0.2-0.6,0.4-0.8,0.5c-0.6,0.4-1.2,0.7-1.9,0.9c-0.9,0.2-1.8,0.2-2.7,0c-0.5-0.1-0.9-0.4-1.2-0.8
	c-0.3-0.4-0.4-0.8-0.3-1.3c0.1-0.4,0.2-0.8,0.2-1.2c0-0.2,0-0.4,0-0.6c-0.1-0.3-0.3-0.3-0.5-0.2c-0.2,0.1-0.3,0.2-0.4,0.4
	c-0.4,0.8-0.9,1.6-1.3,2.4c-0.2,0.3-0.4,0.7-0.5,1c-0.1,0.3-0.3,0.5-0.6,0.5c-0.3,0.1-0.6,0.1-0.9,0c-0.2-0.1-0.3-0.2-0.4-0.4
	c-0.1-0.3-0.1-0.5-0.2-0.8c-0.1-0.4-0.2-0.9-0.3-1.3c0,0,0,0,0-0.1c-0.1,0.1-0.1,0.2-0.2,0.3c-0.5,0.8-1.1,1.4-1.9,1.8
	c-0.6,0.4-1.2,0.5-1.9,0.5c-0.5,0-1-0.2-1.4-0.4c-0.2-0.2-0.4-0.4-0.6-0.6c0,0,0-0.1-0.1-0.1c-0.1,0.1-0.3,0.2-0.4,0.4
	c-0.2,0.2-0.4,0.4-0.6,0.5c-0.4,0.3-0.9,0.3-1.3,0.1c-0.4-0.2-0.6-0.5-0.6-0.9V7.7c0,0,0,0.1-0.1,0.1c-0.2,0.3-0.5,0.6-0.9,0.8
	c-0.6,0.3-1.6,0.2-2.1-0.4c-0.3-0.3-0.4-0.7-0.5-1.1c0-0.2,0-0.3-0.1-0.5c0,0,0,0.1,0,0.1C9.8,7.3,9.4,7.9,8.9,8.2
	c-0.6,0.4-1.3,0.6-2,0.6c-0.5,0-1-0.2-1.4-0.5c-0.1,0-0.1-0.1-0.2-0.1C5.1,8.4,4.8,8.5,4.4,8.6C3.9,8.8,3.3,8.8,2.7,8.8
	c-0.4,0-0.7-0.1-1.1-0.3C1.1,8.2,0.9,7.8,0.9,7.2C1,6.8,1,6.4,1.1,6.1c0.1-0.5,0.1-1,0.2-1.4c0-0.1,0-0.2,0-0.2H1.2
	c-0.3,0-0.6,0-0.8,0C0.2,4.4,0.1,4.3,0,4.1c0-0.2,0-0.3,0.1-0.5c0.1-0.1,0.2-0.2,0.3-0.2c0.2,0,0.4-0.1,0.5-0.1
	c0.4-0.1,0.7-0.4,0.8-0.8c0.1-0.3,0.1-0.7,0.2-1c0-0.2,0.1-0.3,0.3-0.3C2.4,1.1,2.7,1.1,3,1.1c0.2,0,0.3,0,0.5,0.1
	c0.2,0,0.2,0.1,0.1,0.3C3.5,1.9,3.5,2.3,3.4,2.8c0,0.1,0,0.2-0.1,0.4c0.2,0,0.3,0,0.5,0c0.3,0,0.5,0,0.8-0.1c0.1,0,0.2,0,0.2,0.2
	c0.1,0.3,0,0.5-0.1,0.8c0,0.1-0.1,0.2-0.1,0.3C4.5,4.3,4.4,4.4,4.3,4.4c-0.4,0-0.7,0-1.1,0c0,0.2-0.1,0.4-0.1,0.6
	C3,5.3,3,5.7,2.9,6.1c0,0.2,0,0.4,0,0.7c0,0.3,0.2,0.5,0.5,0.5c0.4,0.1,0.8-0.1,1.1-0.2c0,0,0.1,0,0.1,0c0,0,0,0,0,0
	C4.6,6.7,4.6,6.4,4.6,6.1c0-0.7,0.3-1.4,0.7-2C5.8,3.5,6.4,3,7.2,2.9C8,2.8,8.7,2.8,9.4,3.3c0.5,0.4,0.8,0.9,0.8,1.4
	c0,0.2,0,0.3,0.1,0.5c0.7-1.7,1.9-2.6,3.8-2.4c0.1-0.3,0.1-0.6,0.1-1c0.1-0.4,0.1-0.9,0.2-1.3C14.6,0.2,14.8,0,15.2,0
	c0.2,0,0.5,0,0.7,0.1c0.3,0.1,0.4,0.3,0.4,0.6c-0.1,0.5-0.1,0.9-0.2,1.4C16,2.5,16,2.9,15.9,3.4c-0.1,0.4-0.1,0.9-0.2,1.3
	S15.6,5.5,15.5,6c0,0.3-0.1,0.6-0.1,0.8c0,0.2,0.1,0.3,0.3,0.2c0.2-0.1,0.3-0.2,0.5-0.3c0,0,0,0,0-0.1c-0.1-1.1,0.3-2,1-2.8
	c0.5-0.6,1.1-0.9,1.9-1c0.5-0.1,1,0,1.5,0.2c0.6,0.3,0.8,0.8,0.8,1.4c0,0.6-0.4,1-0.8,1.3c-0.5,0.4-1,0.6-1.6,0.8
	c-0.2,0.1-0.5,0.1-0.8,0.2c0,0.3,0.3,0.7,0.6,0.7c0.3,0.1,0.7,0,1-0.1C20.5,7,21,6.6,21.5,5.9c0.3-0.5,0.5-1,0.5-1.6
	c0-0.2,0-0.3-0.1-0.5c-0.1-0.5,0.3-1,0.9-1c0.4,0,0.7,0.2,0.8,0.6c0.1,0.2,0.1,0.5,0.2,0.7c0.1,0.6,0.2,1.2,0.3,1.8c0,0,0,0,0,0
	c0,0,0-0.1,0.1-0.1c0.2-0.5,0.5-1,0.7-1.4c0.3-0.5,0.7-0.9,1.1-1.2c0.4-0.3,0.9-0.4,1.5-0.4c0.3,0,0.7,0.1,1,0.3
	c0.4,0.2,0.6,0.5,0.7,1c0.1,0.4,0.1,0.9,0,1.3c-0.1,0.3-0.1,0.7-0.2,1C29,6.7,29,7,29.1,7.2c0.1,0.3,0.4,0.6,0.8,0.6
	c0.4,0,0.8,0,1.2-0.3c0.2-0.1,0.3-0.3,0.4-0.5c0.1-0.2,0-0.4-0.1-0.6C31,6.2,30.7,6,30.4,5.7c-0.3-0.2-0.5-0.6-0.6-0.9
	c-0.1-0.6,0.1-1.1,0.5-1.4c0.4-0.3,0.8-0.4,1.2-0.5c0.7-0.1,1.3,0,2,0.3c0.2,0.1,0.3,0.1,0.4,0.3c0.2,0.2,0.2,0.4,0.1,0.7
	c-0.1,0.2-0.3,0.4-0.5,0.4c-0.1,0-0.2,0-0.4-0.1c-0.3-0.1-0.7-0.2-1.1-0.1c-0.1,0-0.3,0.1-0.4,0.1c-0.2,0.1-0.2,0.4,0,0.5
	C32,4.9,32.1,5,32.3,5.1c0.4,0.2,0.7,0.5,1,0.8c0.1,0.2,0.2,0.3,0.3,0.6c0.1-0.1,0.3-0.1,0.4-0.2c0,0,0,0,0-0.1
	c0.1-0.8,0.3-1.5,0.8-2.2c0.5-0.6,1.1-1.1,2-1.2c0.5-0.1,1,0,1.5,0.2c0.6,0.3,0.9,0.8,0.9,1.4c0,0.6-0.3,1-0.7,1.3
	c-0.5,0.4-1.1,0.6-1.7,0.8c-0.3,0.1-0.5,0.1-0.8,0.2c-0.1,0-0.1,0-0.1,0.1c0.1,0.5,0.5,0.8,1,0.7c0.5-0.1,1-0.4,1.4-0.7
	c0.1-0.1,0.2-0.2,0.4-0.3c0.2-0.1,0.3-0.1,0.5,0c0.1,0.1,0.2,0.3,0.1,0.5c-0.1,0.2-0.1,0.3-0.2,0.4c-0.6,0.6-1.2,1.1-2.1,1.2
	c-0.5,0.1-1,0.1-1.5,0c-0.7-0.2-1.2-0.7-1.4-1.4C34.2,7.4,34.2,7.3,34.1,7.3z M8.4,5.5c0-0.3,0-0.6-0.2-0.9C8.1,4.5,7.8,4.3,7.6,4.3
	C7.4,4.3,7.1,4.5,7,4.7C6.6,5.3,6.4,6,6.6,6.8c0.2,0.6,0.7,0.6,1.1,0.4C7.9,7,8.1,6.8,8.2,6.6C8.3,6.3,8.4,5.9,8.4,5.5z M13.9,4.4
	c-0.2,0-0.3,0-0.5,0c-0.4,0-0.7,0.1-0.9,0.5c-0.4,0.5-0.6,1.1-0.5,1.7c0,0.2,0.1,0.5,0.4,0.6c0.2,0.1,0.5,0.1,0.7-0.1
	c0.2-0.2,0.3-0.4,0.5-0.5c0.1-0.1,0.1-0.2,0.1-0.3C13.7,5.6,13.8,5,13.9,4.4L13.9,4.4z M18.1,5.8c0.5-0.1,1.1-0.3,1.4-0.7
	c0.1-0.1,0.2-0.3,0.2-0.4c0.1-0.3-0.1-0.6-0.5-0.6c-0.2,0-0.4,0.1-0.5,0.2c-0.2,0.2-0.3,0.3-0.4,0.6C18.2,5.1,18.2,5.5,18.1,5.8
	L18.1,5.8z M36,5.9c0.6-0.1,1.1-0.3,1.5-0.7c0.1-0.1,0.2-0.3,0.2-0.4c0.1-0.3-0.1-0.5-0.4-0.6c-0.2-0.1-0.5,0-0.6,0.2
	c-0.3,0.3-0.5,0.6-0.6,1C36,5.5,36,5.7,36,5.9L36,5.9z"/>
						<path class="img_logo" id="p1" d="M28.7,2.1c-0.7,0-1-0.4-1-1.1c0.1-0.7,0.8-1.2,1.5-0.9c0.4,0.2,0.6,0.6,0.6,1c-0.1,0.5-0.4,0.8-0.9,1
	C28.8,2.1,28.7,2.1,28.7,2.1z"/>
						<path class="img_logo" id="p2" d="M35.6,11.3c0,0.3-0.1,0.5-0.2,0.6c-0.1,0.2-0.3,0.2-0.5,0.2c-0.1,0-0.2,0-0.3-0.1c-0.1,0-0.1-0.1-0.2-0.2l0,0.2h-0.3V9.5
	h0.3v1c0-0.1,0.1-0.1,0.2-0.2c0.1,0,0.2-0.1,0.2-0.1c0.2,0,0.4,0.1,0.5,0.3C35.6,10.7,35.6,11,35.6,11.3L35.6,11.3z M35.3,11.2
	c0-0.2,0-0.3-0.1-0.5c0-0.1-0.1-0.2-0.3-0.2c-0.1,0-0.1,0-0.2,0.1c-0.1,0.1-0.1,0.1-0.1,0.2v0.9c0,0.1,0.1,0.1,0.1,0.2
	c0.1,0,0.1,0.1,0.2,0.1c0.1,0,0.2-0.1,0.3-0.2C35.3,11.6,35.3,11.4,35.3,11.2L35.3,11.2z"/>
						<path class="img_logo" id="p2" d="M36.6,12.2c-0.2,0-0.4-0.1-0.5-0.2c-0.1-0.2-0.2-0.4-0.2-0.6v-0.1c0-0.3,0.1-0.5,0.2-0.7c0.1-0.2,0.3-0.3,0.5-0.3
	c0.2,0,0.4,0.1,0.5,0.2c0.1,0.1,0.2,0.3,0.2,0.6v0.2h-1c0,0.2,0,0.3,0.1,0.5c0.1,0.1,0.2,0.2,0.3,0.2c0.2,0,0.3-0.1,0.4-0.2l0.1,0.2
	c-0.1,0.1-0.1,0.1-0.2,0.2C36.9,12.1,36.7,12.2,36.6,12.2z M36.6,10.5c-0.1,0-0.2,0.1-0.2,0.1c-0.1,0.1-0.1,0.2-0.1,0.4h0.6v0
	c0-0.1,0-0.2-0.1-0.3C36.8,10.6,36.7,10.5,36.6,10.5z"/>
						<path class="img_logo" id="p2" fill="yellow" d="M38,9.9v0.5h0.3v0.2H38v1.1c0,0.1,0,0.1,0,0.2c0,0,0.1,0.1,0.1,0.1c0,0,0,0,0.1,0c0,0,0,0,0.1,0l0,0.2c0,0-0.1,0-0.1,0
	c0,0-0.1,0-0.1,0c-0.1,0-0.2,0-0.3-0.1c-0.1-0.1-0.1-0.2-0.1-0.4v-1.1h-0.2v-0.2h0.2V9.9L38,9.9z"/>
						<path class="img_logo" id="p2" d="M39.5,12.1c0-0.1,0-0.1,0-0.1c0,0,0-0.1,0-0.1c0,0.1-0.1,0.2-0.2,0.2c-0.1,0.1-0.2,0.1-0.3,0.1c-0.1,0-0.3,0-0.4-0.1
	c-0.1-0.1-0.1-0.3-0.1-0.4c0-0.2,0.1-0.3,0.2-0.4c0.1-0.1,0.3-0.1,0.5-0.1h0.3v-0.2c0-0.1,0-0.2-0.1-0.3c-0.1-0.1-0.3-0.1-0.4,0
	c0,0,0,0,0,0c0,0.1-0.1,0.1-0.1,0.2h-0.3l0,0c0-0.1,0.1-0.3,0.2-0.4c0.1-0.1,0.3-0.2,0.5-0.2c0.2,0,0.3,0.1,0.4,0.2
	c0.1,0.1,0.2,0.3,0.2,0.5v0.8c0,0.1,0,0.1,0,0.2c0,0.1,0,0.1,0,0.2L39.5,12.1z M39.1,11.9c0.1,0,0.2,0,0.2-0.1
	c0.1-0.1,0.1-0.1,0.1-0.2v-0.3h-0.3c-0.1,0-0.2,0-0.2,0.1c-0.1,0.1-0.1,0.2-0.1,0.2c0,0.1,0,0.1,0.1,0.2
	C38.9,11.9,39,11.9,39.1,11.9L39.1,11.9z"/>
					</svg>
				</a>
			</div>
			<div class="col-xs-8 col-sm-10 col-md-7 col-lg-7-5">
				<form class="navbar-form navbar-left navbar-searcher mobile" action="<?=Url::to(["/works"])?>" method="get">
					<div class="input-group searcher-header">
						<input type="text" name="q" value="<?=$q?>" class="form-control" translate-attr="{placeholder: 'global.SEARCH'}">
                        <!-- select of search objects -->
                        <select name="searchTypeId">
                            <option value="">All</option>
                            <option value="{{ searchType.id }}" ng-repeat="searchType in publicHeaderCtrl.searchTypes" translate="{{ searchType.name }}" ng-selected="searchType.id == publicHeaderCtrl.selectedSearchTypeId"></option>
                        </select>
						<span class="input-group-btn">
							<button class="btn btn-default btn-send" type="submit">
								<span class="ion-search"></span>
							</button>
						</span>
					</div>
				</form>
				<form class="navbar-form navbar-left navbar-searcher" action="<?=Url::to(['/works'])?>" method="get">
					<div class="input-group searcher-header">
						<input type="text" name="q" value="<?=$q?>" class="form-control" translate-attr="{placeholder: 'global.SEARCH'}">
                        <!-- select of search objects -->
                        <select name="searchTypeId">
                            <option value="">All</option>
                            <option value="{{ searchType.id }}" ng-repeat="searchType in publicHeaderCtrl.searchTypes" translate="{{ searchType.name }}" ng-if="searchType.id < 100" ng-selected="searchType.id == publicHeaderCtrl.selectedSearchTypeId"></option>
                        </select>
						<span class="input-group-btn">
							<button class="btn btn-default btn-send" type="submit">
								<span class="ion-ios-search-strong"></span>
							</button>
						</span>
					</div>
				</form>
			</div>
			<div class="hidden-xs hidden-sm col-md-3 col-lg-2-5">
				<ul class="nav navbar-nav navbar-right cart-login-wrapper">
					<?php if (Yii::$app->user->isGuest) { ?>
						<li class="log">
							<a href="<?=Url::to(['/signup'])?>" translate="header.SIGNUP"></a>
						</li>
						<li class="log">
							<span translate="header.OR"></span>
						</li>
						<li class="log">
							<a href="<?=Url::to('/login')?>" translate="header.LOGIN"></a>
						</li>
					<?php } else {
						$person = Yii::$app->user->identity; /* @var \app\models\Person $person */?>
						<li class="dropdown log">
							<a class="logued-text" href="#" class="dropdown-toggle log" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img class="avatar-logued-user" src="<?= $person->getProfileImage(50, 50) ?>"></a>
							<div class="dropdown-menu admin-wrapper black-form">
								<ul class="menu-logued">
									<li class="header-item">
										<a href="<?= $person->getMainLink()?>"><span><?=$person->getName()?></span></a>
									</li>
									<?php if ($person->isAdmin()) { ?>
										<li><a href="<?=Url::to('/admin')?>" translate="header.ADMINISTRATION"></a></li>
										<li><a href="<?=Url::to('/admin/invitations')?>" translate="header.INVITATION"></a></li>
										<li class="separation-line"></li>
									<?php } elseif ($person->isDeviser()) { ?>
										<li><a href="<?=$person->getSettingsLink('open-orders')?>" translate="header.SALES"></a></li>
										<li class="separation-line"></li>
									<?php } elseif ($person->isClient()) { ?>
										<li><a href="<?=$person->getSettingsLink('open-orders')?>" translate="header.MY_ORDERS"></a></li>
										<li class="separation-line"></li>
									<?php } elseif ($person->isInfluencer()) { ?>
									<?php } ?>
									<li><a href="<?= $person->getSettingsLink()?>" translate="header.SETTINGS"></a></li>
									<li><a href="#" ng-click="publicHeaderCtrl.logout()" translate="header.LOGOUT"></a></li>
								</ul>
							</div>
						</li>
					<?php } ?>
					<li class="chat-item">
						<a href="<?=Url::to(['/messages'])?>">
							<span class="badge badge-notify bg-red" ng-if="publicHeaderCtrl.msgQuantity>0" ng-cloak ng-bind="publicHeaderCtrl.msgQuantity" style="margin-left: 10px;z-index: 999999;position: absolute;top:7px;"></span>
							<span class="icons-hover chat-icon"></span>
						</a>
					</li>
					<li class="cart-item">
						<a href="<?=Url::to(['/cart'])?>">
							<span class="badge badge-notify bg-red" ng-if="publicHeaderCtrl.cartQuantity>0" ng-cloak ng-bind="publicHeaderCtrl.cartQuantity" style="margin-left: 10px;z-index: 999999;position: absolute;top:7px;"></span>
							<span class="icons-hover cart-icon"></span>
						</a>
					</li>
				</ul>
			</div>
			</div>
		</div>
	</nav>
	<nav id="main_nav">
	</nav>
</header>


<div id="navbar-wrapper" ng-mouseleave="publicHeaderCtrl.openMenu = false">
	<nav class="navbar navbar-default secondary">
		<div class="container" id="main_menu">
			<div class="navbar-elements">
				<ul class="nav navbar-nav">
					<li class="col-xs-2 col-sm-2 col-md-12 col-lg-12">
						<a href="#" class="menu-title" ng-mouseover="publicHeaderCtrl.openMenu = true" ng-click="publicHeaderCtrl.openMenu=!publicHeaderCtrl.openMenu">
							<i class="fa" ng-class="publicHeaderCtrl.openMenu ? 'ion-android-close black-icon' : 'fa-bars'" aria-hidden="true"></i>
							<span translate="header.SHOP_BY_DEPARTMENT"></span>
						</a>
					</li>
					<li id="mobile-iconset" class="hidden-md hidden-lg col-xs-10 col-sm-10" >
						<div id="separator_right">
							<?php if (Yii::$app->user->isGuest) { ?>
							<a href="<?=Url::to('/login')?>"><img src="/imgs/login-red.svg" /></a>
							<?php } else {
								$person = Yii::$app->user->identity; /* @var \app\models\Person $person */?>
								<span class="dropdown">
									<a class="logued-text" href="#" class="dropdown-toggle log" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="/imgs/login-red.svg" /></a>
										<div class="dropdown-menu admin-wrapper black-form">
											<ul class="menu-logued">
												<li class="header-item">
													<a href="<?= $person->getMainLink()?>"><span><?=$person->getName()?></span></a>
												</li>
											<?php if ($person->isAdmin()) { ?>
												<li><a href="<?=Url::to('/admin')?>" translate="header.ADMINISTRATION"></a></li>
												<li><a href="<?=Url::to('/admin/invitations')?>" translate="header.INVITATION"></a></li>
												<li class="separation-line"></li>
											<?php } elseif ($person->isDeviser()) { ?>
												<li><a href="<?=$person->getSettingsLink('open-orders')?>" translate="header.SALES"></a></li>
												<li class="separation-line"></li>
											<?php } elseif ($person->isClient()) { ?>
												<li><a href="<?=$person->getSettingsLink('open-orders')?>" translate="header.MY_ORDERS"></a></li>
												<li class="separation-line"></li>
											<?php } elseif ($person->isInfluencer()) { ?>
											<?php } ?>
											<li><a href="<?= $person->getSettingsLink()?>" translate="header.SETTINGS"></a></li>
											<li><a href="#" ng-click="publicHeaderCtrl.logout()" translate="header.LOGOUT"></a></li>
										</ul>
									</div>
								</span>
							<?php } ?>
							<a href="<?=Url::to('/messages')?>"><img src="/imgs/chat-red.svg" /></a>
							<a href="<?=Url::to(['/cart'])?>"><img src="/imgs/cart-red.svg" /></a>
						</div>
						<div id="separator_center">
							<a href="<?=Url::to(['/timeline'])?>"><img src="/imgs/timeline-red.svg" /></a>
							<a href="<?=Url::to(['/discover/boxes'])?>"><img src="/imgs/box-red.svg" /></a>
							<a href="<?=Url::to(['/discover/devisers'])?>"><img src="/imgs/discover-red.svg" /></a>
							<a href="<?=Url::to(['/discover/influencers'])?>"><img src="/imgs/estrella-red.svg" /></a>
						</div>
					</li>
				</ul>
				<ul class="nav navbar-nav center-navbar">
					<?php /*<li><a href="<?=Url::to(['/discover/stories'])?>" translate="header.STORIES"></a></li>*/?>
					<li><a href="<?=Url::to(['/timeline'])?>" translate="header.TIMELINE"></a></li>
					<li><a href="<?=Url::to(['/discover/boxes'])?>" translate="header.EXPLORE_BOXES"></a></li>
					<li><a href="<?=Url::to(['/discover/devisers'])?>" translate="header.DISCOVER_DEVISERS"></a></li>
					<li><a href="<?=Url::to(['/discover/influencers'])?>" translate="header.TREND_SETTERS"></a></li>
				</ul>
				<div class="language-picker-wrapper">
						<?php

						echo LanguagePicker::widget([
							'skin' => LanguagePicker::SKIN_BUTTON,
							'size' => LanguagePicker::SIZE_SMALL,
							'parentTemplate' => '<div class="language-picker button-list pull-right {size}"><div>{items}</div></div>',
							'itemTemplate' => '<a href="{link}" title="{name}" class="{language}">{name}</a>',
							'activeItemTemplate' => '<a href="{link}" title="{name}" class="{language} active">{name}</a>',
							'languageAsset' => 'lajax\languagepicker\bundles\LanguageLargeIconsAsset',
							'languagePluginAsset' => 'lajax\languagepicker\bundles\LanguagePluginAsset',
						]);
						?>
				</div>
			</div>
		</div>
	</nav>
	<div class="menu-categories" ng-class="publicHeaderCtrl.openMenu ? 'active' : ''">
		<nav class="navbar navbar-default terciary">
			<div class="hidden-xs hidden-sm container"><!--DESKTOP-->
				<ul>
					<?php foreach($categories as $category) { ?>
						<li>
							<a href="<?=$category->getMainLink()?>" ng-mouseover="publicHeaderCtrl.selectedCategory = '<?=$category->short_id?>'" ng-class="publicHeaderCtrl.selectedCategory == '<?=$category->short_id?>' ?  'selected' : ''">
								<?= $category->name?>
							</a>
							<span class="hidden-md hidden-lg glyphicon glyphicon-plus" aria-hidden="true"></span>
						</li>
					<?php } ?>
				</ul>
			</div>
			<div class="hidden-md hidden-lg container"><!--MOBILE / TABLET-->
				<ul>
				<?php foreach($categories as $category) { ?>
					<li>
						<a href="<?= $category->getMainLink()?>"><?= $category->name?></a>
						<a href="#" ng-click="publicHeaderCtrl.selectedCategory = '<?=$category->short_id?>'" ng-class="publicHeaderCtrl.selectedCategory == '<?=$category->short_id?>' ?  'selected' : ''" aria-hidden="true">
							<span class="glyphicon glyphicon-plus"></span>
						</a>
					</li>
					<?php } ?>
				</ul>
			</div>
		</nav>
		<div id="submenu-categories">
			<?php foreach($categories as $category) { ?>
				<div class="category-menu" ng-class="publicHeaderCtrl.selectedCategory == '<?=$category->short_id?>' ? 'active' : ''">
					<div class="container">
						<div class="categories">
							<ul>
								<?php
								if ($category->hasGroupsOfCategories()) {
									// Category with 3 levels or more.
									// Each 2nd level is shown as a column with a styled title
									// Each column shows 3rd level items
									$subCategories = $category->getSubCategoriesHeader();
									if ($subCategories) {
										foreach ($subCategories as $subCategory) { ?>
											<ul class="two-categories">
												<li>
													<a class="two-categories-title"
													   href="<?= $subCategory->getMainLink() ?>"><?= $subCategory->name ?></a>
												</li>
												<?php

												$subSubCategories = $subCategory->getSubCategoriesHeader();
												foreach ($subSubCategories as $subSubCategory) { ?>
													<li>
														<a href="<?= $subSubCategory->getMainLink() ?>"><?= $subSubCategory->name ?></a>
													</li>
													<?php
												} ?>
											</ul>
											<?php
										}
									}
								} else {
									$subCategories = $category->getSubCategoriesHeader();
									if ($subCategories) {
										if (count($subCategories) > 8) {
											// Category with 9 or more 2nd level items, subcategories are shown in columns ?>
											<ul class="two-categories">
											<?php
										}
										$i = 1;
										foreach ($subCategories as $subCategory) { ?>
											<li>
												<a href="<?= $subCategory->getMainLink() ?>"><?= $subCategory->name ?></a>
											</li>

											<?php if (count($subCategories) > 8 && $i == ceil(count($subCategories) / 2)) { ?>
												</ul>
												<ul class="two-categories">
												<?php
											}
											$i++;
										}
										if (count($subCategories) > 8) { ?>
											</ul>
											<?php
										}
									}
								} ?>
							</ul>
						</div>
						<div class="images">
							<?php
							$headerImages = $category->getHeaderImages();
							$count = 1;
							foreach ($headerImages as $image) { ?>
							<div class="image-<?=$count?>">
								<?php if ($image['link']) { ?>
								<a href="<?=$image['link']?>" title="<?=$image['name']?>">
									<img src="<?=$image['url']?>">
								</a>
								<?php } else { ?>
								<img src="<?=$image['url']?>">
								<?php } ?>
							</div>
							<?php
							if ($count== 1) {
								$count = 2;?>
								<div class="images-wrapper">
									<?php }
								}
								if (count($headerImages)) { ?>
							</div><!--close image-wrapper-->
							<?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
</div>

