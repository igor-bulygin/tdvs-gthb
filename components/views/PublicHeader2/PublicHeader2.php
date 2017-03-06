<?php
use app\helpers\Utils;
use app\models\Category;
use yii\helpers\Url;

/** @var Category $category */

app\components\assets\PublicHeader2Asset::register($this);

?>

<nav class="navbar navbar-default" ng-controller="publicHeaderCtrl as publicHeaderCtrl">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<a class="navbar-brand" href="<?= Url::to(["public/index"])?>">
				<img src="/imgs/logo.png">
			</a>
		</div>
		<form class="navbar-form navbar-left navbar-searcher mobile" action="<?=Url::to(["/works"])?>" method="get">
			<div class="input-group searcher-header">
				<input type="text" name="q" value="<?=$q?>" class="form-control" placeholder="Search">
				<span class="input-group-btn">
                    <button class="btn btn-default btn-send" type="submit">
                        <span class="ion-search"></span>
                    </button>
                </span>
			</div>
		</form>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div>
			<ul class="nav navbar-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle menu-title" data-toggle="dropdown" role="button"
					   aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-bars" aria-hidden="true"></i>
						<span>Shop by departament</span>
					</a>
					<div class="dropdowns-wrapper">
						<div class="dropdown-menu dropdown-shop">
							<ul class="shop-menu-wrapper">
								<?php foreach($categories as $category) { ?>
									<li class="toggle-category" data-target=".category-<?=$category->short_id?>"><a class="ion-chevron-right" href="<?= Url::to(["public/category-b", "slug" => $category->slug, 'category_id' => $category->short_id])?>"><?= Utils::l($category->name)?></a></li>
									<li role="separator" class="divider"></li>
								<?php } ?>
							</ul>
							<?php
							$active = 'active';
							foreach ($categories as $category) {
								if ($category->hasGroupsOfCategories()) {
									$subCategories = $category->getSubCategories();
									if ($subCategories) {
										foreach ($subCategories as $subCategory) {

											$subSubCategories = $subCategory->getSubCategoriesHeader(); ?>
											<ul class="shop-secondary-menu-wrapper category category-<?=$category->short_id ?> <?=$active?>">
												<li><?= Utils::l($subCategory->name) ?></li>
												<?php foreach ($subSubCategories as $subSubCategory) { ?>
													<li>
														<a href="<?= Url::to(["public/category-b", "slug" => $subSubCategory->slug, 'category_id' => $subSubCategory->short_id]) ?>"><?= Utils::l($subSubCategory->name) ?></a>
													</li>
												<?php }
												if (($image = $subCategory->getHeaderImage()) !== null) { ?>
													<li class="minibanner">
													<a href="#">
														<img src="<?= $image ?>">
													</a>
													</li><?php
												} ?>
											</ul>
										<?php }
									}
								} else {
									$subCategories = $category->getSubCategoriesHeader();
									if ($subCategories) { ?>
										<ul class="shop-secondary-menu-wrapper category category-<?=$category->short_id ?> <?=$active?>">
											<?php foreach ($subCategories as $subCategory) { ?>
												<li>
													<a href="<?= Url::to(["public/category-b", "slug" => $subCategory->slug, 'category_id' => $subCategory->short_id]) ?>"><?= Utils::l($subCategory->name) ?></a>
												</li><?php
											}
											if (($image = $category->getHeaderImage()) !== null) { ?>
												<li class="minibanner">
													<a href="#">
														<img src="<?= $image ?>">
													</a>
												</li><?php
											} ?>
										</ul><?php
									}
								}
								$active = '';
							} ?>
						</div>
					</div>
				</li>
			</ul>
			<form class="navbar-form navbar-left navbar-searcher" action="<?=Url::to(['/works'])?>" method="get">
				<div class="input-group searcher-header">
					<input type="text" class="form-control" name="q" value="<?=$q?>"placeholder="Search">
					<span class="input-group-btn">
                            <button class="btn btn-default btn-send" type="submit">
                                <span class="ion-search"></span>
                            </button>
                        </span>
				</div>
			</form>
			<ul class="nav navbar-nav navbar-right cart-login-wrapper">
				<li class="cart-item">
					<a href="<?=Url::to(['/cart'])?>">
						<i class="ion-ios-cart active"></i>
					</a>
				</li>
				<?php if (Yii::$app->user->isGuest) { ?>
					<li class="log">
						<a href="<?=Url::to(['/signup'])?>">
							Sign up
						</a>
					</li>
					<li class="log">
						<span>or</span>
					</li>
					<li class="log">
						<a href="<?=Url::to('/login')?>">Log in</a>
					</li>
				<?php } else {
					$person = Yii::$app->user->identity; /* @var \app\models\Person $person */?>
						<li class="dropdown log">

							<img class="avatar-logued-user" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($person->getAvatarImage())->resize(25, 25) ?>">

							<a class="logued-text" href="#" class="dropdown-toggle log" data-toggle="dropdown" role="button" aria-haspopup="true"
							   aria-expanded="false">My todevise</a>

							<div class="dropdown-menu admin-wrapper black-form">

								<ul class="menu-logued">
									<?php if ($person->isAdmin()) { ?>

										<li class="header-item">
											<span><?=$person->personalInfoMapping->getBrandName()?></span>
											<img class="avatar-logued-user" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($person->getAvatarImage())->resize(25, 25) ?>">
										</li>
										<li><a href="<?=Url::to('/admin')?>">Administration</a></li>
										<li><a href="<?=Url::to('/admin/invitations')?>">Invitations</a></li>

									<?php } elseif ($person->isDeviser()) { ?>

										<li class="header-item">
											<a href="<?= $person->getAboutLink()?>"> <span><?=$person->personalInfoMapping->getBrandName()?></span></a>
											<img class="avatar-logued-user" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($person->getAvatarImage())->resize(25, 25) ?>">
										</li>
										<li><a href="#">Sales</a></li>

									<?php } elseif ($person->isClient()) { ?>

										<li class="header-item">
											<span><?=$person->personalInfoMapping->getBrandName()?></span>
											<img class="avatar-logued-user" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($person->getAvatarImage())->resize(25, 25) ?>">
										</li>
										<li><a href="#"><?=$person->personalInfoMapping->getBrandName()?></a></li>
										<li><a href="#">My orders</a></li>

									<?php } elseif ($person->isInfluencer()) { ?>

                                        <li class="header-item">
                                            <a href="<?= $person->getAboutLink()?>"> <span><?=$person->personalInfoMapping->getBrandName()?></span></a>
                                            <img class="avatar-logued-user" src="<?= Utils::url_scheme() ?><?= Utils::thumborize($person->getAvatarImage())->resize(25, 25) ?>">
                                        </li>

									<?php }?>

									<li class="separation-line"></li>
									<li><a href="<?= Url::to(["settings/index", "slug" => $person->slug, 'person_id' => $person->short_id])?>">Settings</a></li>
									<li><a href="#" ng-click="publicHeaderCtrl.logout()">Logout</a></li>
								</ul>
							</div>
						</li>
				<?php } ?>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container -->
</nav>
