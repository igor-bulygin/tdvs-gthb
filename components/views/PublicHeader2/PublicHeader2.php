<?php

use app\models\Category;
use yii\helpers\Url;
use lajax\languagepicker\widgets\LanguagePicker;

/** @var Category $category */

app\components\assets\PublicHeader2Asset::register($this);

?>

<nav class="navbar navbar-default" ng-controller="publicHeaderCtrl as publicHeaderCtrl">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="row">
		<div class="navbar-header">
			<a class="navbar-brand" href="<?= Url::to(["public/index"])?>">
				<img src="/imgs/logo.png" id="header_logo">
			</a>
		</div>
		<form class="navbar-form navbar-left navbar-searcher mobile" action="<?=Url::to(["/works"])?>" method="get">
			<div class="input-group searcher-header">
				<input type="text" name="q" value="<?=$q?>" class="form-control" translate-attr="{placeholder: 'global.SEARCH'}">
				<span class="input-group-btn">
					<button class="btn btn-default btn-send" type="submit">
						<span class="ion-search"></span>
					</button>
				</span>
			</div>
		</form>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div>
			<div class="searcher-wrapper">
				<form class="navbar-form navbar-left navbar-searcher" action="<?=Url::to(['/works'])?>" method="get">
					<div class="input-group searcher-header">
						<input type="text" class="form-control" name="q" value="<?=$q?>" translate-attr="{placeholder: 'global.SEARCH'}">
						<span class="input-group-btn">
								<button class="btn btn-default btn-send" type="submit">
									<span class="ion-ios-search-strong"></span>
								</button>
							</span>
					</div>
				</form>
			</div>
			<ul class="nav navbar-nav navbar-right cart-login-wrapper">
				<?php if (Yii::$app->user->isGuest) { ?>
					<li class="log">
						<a href="<?=Url::to(['/signup'])?>">
							Sign up
						</a>
					</li>
					<li class="log">
						<span>/</span>
					</li>
					<li class="log">
						<a href="<?=Url::to('/login')?>" translate="header.LOGIN"></a>
					</li>
				<?php } else {
					$person = Yii::$app->user->identity; /* @var \app\models\Person $person */?>
						<li class="dropdown log">
							<a class="logued-text" href="#" class="dropdown-toggle log" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" translate="header.MY_TODEVISE"><i class="ion-android-person"></i></a>
							<div class="dropdown-menu admin-wrapper black-form">
								<ul class="menu-logued">
									<li class="header-item">
										<a href="<?= $person->getMainLink()?>"> <span><?=$person->getName()?></span></a>
										<img class="avatar-logued-user" src="<?= $person->getAvatarImage() ?>">
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
				<li class="cart-item">
					<a href="<?=Url::to(['/cart'])?>">
						<i class="ion-ios-cart active"></i>
					</a>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->
		</div>
	</div><!-- /.container -->
</nav>
<div id="navbar-wrapper">
	<nav class="navbar navbar-default secondary">
		<div class="container" id="main_menu">
			<div class="navbar-elements">
				<ul class="nav navbar-nav">
						<li>
							<a href="#" class="menu-title hover-toggle" data-target=".menu-categories" data-group=".category-menu">
								<i class="fa fa-bars" aria-hidden="true"></i>
								<span translate="header.SHOP_BY_DEPARTMENT"></span>
							</a>
						</li>
					</ul>
				<ul class="nav navbar-nav center-navbar">
					<li><a href="<?=Url::to(['/discover/stories'])?>" translate="header.STORIES"></a></li>
					<li><a href="<?=Url::to(['/discover/boxes'])?>" translate="header.EXPLORE_BOXES"></a></li>
					<li><a href="<?=Url::to(['/discover/devisers'])?>" translate="header.DISCOVER_DEVISERS"></a></li>
					<li><a href="<?=Url::to(['/discover/influencers'])?>" translate="header.TREND_SETTERS"></a></li>
					<li><a href="#" translate="header.PROJECTS"></a></li>
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
	<div class="menu-categories">
		<nav class="navbar navbar-default terciary">
			<div class="container">
				<ul>
					<?php foreach($categories as $category) { ?>
						<li>
							<a class="hover-toggle <?=$selectedCategory && $selectedCategory->short_id == $category->short_id ? 'selected' : ''?>" data-group=".category-menu" data-target="#category-<?=$category->short_id?>" href="<?= $category->getMainLink()?>"><?= $category->name?></a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</nav>
		<div id="submenu-categories">
			<?php foreach($categories as $category) { ?>
				<div class="category-menu" id="category-<?=$category->short_id?>">
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
													<a class="two-categories-title" href="<?=$subCategory->getMainLink()?>"><?= $subCategory->name ?></a>
												</li>
												<?php

												$subSubCategories = $subCategory->getSubCategoriesHeader();
												foreach ($subSubCategories as $subSubCategory) { ?>
													<li>
														<a href="<?=$subSubCategory->getMainLink()?>"><?= $subSubCategory->name ?></a>
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
								}?>
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
							if (count($headerImages) > 1) { ?>
									</div><!--close image-wrapper-->
							<?php } ?>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>