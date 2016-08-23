<?php
use yii\web\View;
use app\models\Lang;
use yii\helpers\Html;
use yii\helpers\Json;
use app\helpers\Utils;

/* @var $this \yii\web\View */
/* @var $content string */

?>

<?php $this->beginPage() ?>
<!doctype html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <!-- METAS -->
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php $this->head() ?>
    <!-- CSS -->
    <link href="/css/desktop/public-2/bootstrap.min.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="/css/desktop/public-2/application.css" rel="stylesheet" type="text/css" media="all"/>
    <!-- FONTS -->
    <link href='https://fonts.googleapis.com/css?family=Playfair+Display:400,700italic,400italic,700,900,900italic'
          rel='stylesheet' type='text/css'>
    <!-- ICONS -->
    <!-- IONIC ICONS -->
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- FONTAWESOME ICONS -->
    <script src="https://use.fontawesome.com/9a31e47575.js"></script>
</head>

<body>
<nav class="navbar navbar-default">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">

            <a class="navbar-brand" href="#">
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
                            <li><a class="ion-chevron-right" href="#">Art</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a class="ion-chevron-right" href="#">Fashion</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a class="ion-chevron-right" href="#">Decoration</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a class="ion-chevron-right" href="#">Gadgets</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a class="ion-chevron-right" href="#">Jewlery</a></li>
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
<!-- BANNER-->
<div class="bs-example" data-example-id="simple-carousel">
    <div class="carousel slide" id="carousel-example-generic" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class=""></li>
            <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
            <li data-target="#carousel-example-generic" data-slide-to="2" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="3" class=""></li>
            <li data-target="#carousel-example-generic" data-slide-to="4" class=""></li>
        </ol>
        <div class="carousel-inner" role="listbox">
            <div class="item">
                <img src="/imgs/banner-4.jpg" alt="" title="">
            </div>
            <div class="item">
                <img src="/imgs/banner-5.jpg" alt="" title="">
            </div>
            <div class="item active">
                <img src="/imgs/banner-1.jpg" alt="" title="">
            </div>
            <div class="item">
                <img src="/imgs/banner-2.jpg" alt="" title="">
            </div>
            <div class="item">
                <img src="/imgs/banner-3.jpg" alt="" title="">
            </div>
        </div>
    </div>
</div>
<!-- /BANNER -->
<!-- SUB-BANNER -->
<section class="sub-banner">
    <div class="container container-sub-baner">
        <div class="row">
            <div class="col-sm-4 col-xs-6 title-wrapper righty">
                <h2 class="title-1"><span class="serif">The</span>store</h2>
                <p class="tagline-1">Find products that will make you part of the future</p>
            </div>
            <div class="col-sm-4 col-xs-6 title-wrapper">
                <h2>Social<br/><span class="serif">experience</span></h2>
                <p class="tagline-2">Show the world what you like &amp; build a community</p>
            </div>
            <div class="col-sm-4 title-wrapper">
                <h2>Affiliate<br/><span class="serif">for all</span></h2>
                <p class="tagline-3">Love a product. People buy it. You earn money.</p>
            </div>
        </div>
    </div>
</section>
<!-- /SUB-BANNER -->
<?= $content ?>

<!-- FOOTER -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="title">Categories</div>
                <ul class="footer-items">
                    <li>
                        <a href="#">Art</a>
                    </li>
                    <li>
                        <a href="#">Fashion</a>
                    </li>
                    <li>
                        <a href="#">Industrial design</a>
                    </li>
                    <li>
                        <a href="#">Technology</a>
                    </li>
                    <li>
                        <a href="#">Other</a>
                    </li>
                </ul>
            </div>
            <div class="col-sm-3">
                <div class="title">Help &amp; Contact</div>
                <ul class="footer-items">
                    <li>
                        <a href="#">Contact us</a>
                    </li>
                    <li>
                        <a href="#">Returns &amp; Warranties</a>
                    </li>
                    <li>
                        <a href="#">About us</a>
                    </li>
                </ul>
            </div>
            <div class="col-sm-3">
                <div class="title">Join Todevise</div>
                <button type="button" class="btn red-btn">Become a deviser</button>
            </div>
            <div class="col-sm-3">
                <div class="title">Stay connected</div>
                <div class="footer-text">Subscribe to our newsletter</div>
                <div class="input-group input-newsletter">
                    <input type="text" class="form-control" placeholder="Email">
                    <span class="input-group-btn">
                                <button class="btn btn-default btn-send" type="button">
                                    <span class="ion-forward"></span>
                                </button>
                            </span>
                </div>
                <div class="footer-text social">
                    Follow us
                </div>
                <ul class="social-items">
                    <li>
                        <a href="#">
                            <i class="fa fa-facebook" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a class="twitter" href="#">
                            <i class="fa fa-twitter" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a class="google-plus" href="#">
                            <i class="fa fa-google-plus" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-pinterest-p" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <div>Todevise Copyright 2016</div>
            <div>
                <ul>
                    <li>
                        <a href="#">Terms &amp; Conditions</a>
                    </li>
                    <li>|</li>
                    <li>
                        <a href="#">Privacy policy</a>
                    </li>
                    <li>|</li>
                    <li>
                        <a href="#">Cookies policy</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="/js/desktop/public-2/bootstrap.min.js"></script>
</body>
</html>
<?php $this->endPage() ?>
