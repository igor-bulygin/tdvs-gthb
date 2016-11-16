<?php
use app\helpers\Utils;
use app\models\Category;
use yii\helpers\Html;
use yii\helpers\Url;


use app\components\PublicMyAccount;
//use app\components\assets\publicHeaderAsset;

//publicHeaderAsset::register($this);

/** @var Category $category */

?>

<nav class="navbar navbar-default">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<a class="navbar-brand" href="<?= Url::to(["public/index"])?>">
				<img src="/imgs/logo.png">
			</a>
		</div>
		<form class="navbar-form navbar-left navbar-searcher mobile">
			<div class="input-group searcher-header">
				<input type="text" class="form-control" placeholder="Search">
				<span class="input-group-btn">
                    <button class="btn btn-default btn-send" type="button">
                        <span class="ion-search"></span>
                    </button>
                </span>
			</div>
		</form>

		<!-- Collect the nav links, forms, and other content for toggling -->
			<ul class="nav navbar-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle menu-title" data-toggle="dropdown" role="button"
					   aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-bars" aria-hidden="true"></i>
						<span>Shop by departament</span>
					</a>

				<div class="dropdown-menu dropdown-shop">
						<ul class="shop-menu-wrapper">
							<?php foreach($categories as $category) { ?>
								<li><a class="ion-chevron-right" href="<?= Url::to(["public/category-b", "slug" => $category->slug, 'category_id' => $category->short_id])?>"><?= Utils::l($category->name)?></a></li>
								<li role="separator" class="divider"></li>
							<?php } ?>
							<li><a class="ion-chevron-right" href="/">Gadgets</a></li>
						</ul>
						<ul class="shop-secondary-menu-wrapper">
							<li>Womanswear</li>
							<li>
								<a href="">Accesories</a>
							</li>
							<li>
								<a href="">Clothes</a>
							</li>
							<li>
								<a href="">Intimate Appareal</a>
							</li>
							<li>
								<a href="">Footwear</a>
							</li>
							<li>
								<a href="">Sportswear</a>
							</li>
							<li class="minibanner">
								<a href="#">
									<img src="/imgs/mini-banner-1.jpg">
								</a>
							</li>
						</ul>
						<ul class="shop-secondary-menu-wrapper">
							<li>Menswear</li>
							<li>
								<a href="">Accesories</a>
							</li>
							<li>
								<a href="">Clothes</a>
							</li>
							<li>
								<a href="">Footwear</a>
							</li>
							<li>
								<a href="">Intimate Appareal</a>
							</li>
							<li>
								<a href="">Sportswear</a>
							</li>
							<li class="minibanner">
								<a href="#">
									<img src="/imgs/mini-banner-2.jpg">
								</a>
							</li>
						</ul>
					</div>
				</li>
			</ul>
			<form class="navbar-form navbar-left navbar-searcher">
				<div class="input-group searcher-header">
					<input type="text" class="form-control" placeholder="Search">
					<span class="input-group-btn">
                            <button class="btn btn-default btn-send" type="button">
                                <span class="ion-search"></span>
                            </button>
                        </span>
				</div>
			</form>
			<ul class="nav navbar-nav navbar-right cart-login-wrapper">
				<li class="cart-item">
					<a href="#">
						<i class="ion-ios-cart active"></i>
					</a>
				</li>

				<?= PublicMyAccount::widget() ?>

			</ul>
		</div>
	</div>
</nav>
