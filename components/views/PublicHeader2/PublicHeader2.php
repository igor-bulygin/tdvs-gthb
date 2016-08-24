<?php
use yii\helpers\Html;
use yii\helpers\Url;

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
		<div>
			<ul class="nav navbar-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle menu-title" data-toggle="dropdown" role="button"
					   aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-bars" aria-hidden="true"></i>
						<span>Shop by departament</span>
					</a>

				<div class="dropdown-menu dropdown-shop">
						<ul class="shop-menu-wrapper">
							<li><a class="ion-chevron-right" href="<?= Url::to(["public/category-b", "slug" => 'art', 'category_id' => '1a23b'])?>">Art</a></li>
							<li role="separator" class="divider"></li>
							<li><a class="ion-chevron-right" href="<?= Url::to(["public/category-b", "slug" => 'fashion', 'category_id' => '4a2b4'])?>">Fashion</a></li>
							<li role="separator" class="divider"></li>
							<li><a class="ion-chevron-right" href="/">Decoration</a></li>
							<li role="separator" class="divider"></li>
							<li><a class="ion-chevron-right" href="/">Gadgets</a></li>
							<li role="separator" class="divider"></li>
							<li><a class="ion-chevron-right" href="<?= Url::to(["public/category-b", "slug" => 'jewelry', 'category_id' => '3f78g'])?>">Jewlery</a></li>
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
				<li class="log">
					<a href="#">
						Sign up
					</a>
				</li>
				<li class="log">
					<span>or</span>
				</li>
				<li class="dropdown log">
					<a href="#" class="dropdown-toggle log" data-toggle="dropdown" role="button" aria-haspopup="true"
					   aria-expanded="false">Log in</a>
					<ul class="dropdown-menu login-wrapper">
						<li>
							<input type="text" class="form-control" placeholder="Name">
						</li>
						<li>
							<input type="email" class="form-control" placeholder="Email">
						</li>
						<li class="forgot-remember">
							<a href="#">Forgot your password?</a>
						</li>
						<li>
							<button type="button" class="btn btn-default black-btn">Login</button>
						</li>
					</ul>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
