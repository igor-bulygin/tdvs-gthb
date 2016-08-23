<?php
use yii\web\View;
use yii\helpers\Url;
use app\models\Lang;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\helpers\Utils;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use app\assets\desktop\pub\IndexAsset;
use app\assets\desktop\pub\Index2Asset;

Index2Asset::register($this);

$this->title = 'Todevise / Home';

?>
<!-- GRID -->
<section class="grid-wrapper">
    <div class="container">
        <div class="section-title">
            Highlighted Works
        </div>
        <div>
            <?php foreach ($works12 as $i => $work) { ?>
            <div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize(@$work['img'])->resize(362, 450) ?>">
                            <figcaption>
                                <p class="instauser">
                                    Black dress
                                </p>
                                <p class="price">€ 3.230</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            <?php } ?>
            <?php foreach ($works3 as $i => $work) { ?>
            <div class="col-md-4 col-sm-4 pad-grid pad-grid-h">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize(@$work['img'])->resize(375, 220) ?>">
                            <figcaption>
                                <p class="instauser">
                                    Sculpture conecteur
                                </p>
                                <p class="price">€ 30.230</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<!-- /GRID -->

<!-- SHOWCASE -->
<section class="showcase-wrapper">
    <div class="container">
        <h3>Artists, designers, creators who<br>
            shape outstanding works</h3>
        <div class="section-title">
            Devisers
        </div>
        <div>
            <?php foreach ($devisers as $i => $deviser) { ?>
            <div class="col-md-3 col-sm-3 col-xs-6 pad-showcase">
                <a href="#">
                    <figure class="showcase">
                        <button class="btn btn-default btn-follow"><i class="ion-star"></i><span>Follow</span>
                        </button>
                        <img class="showcase-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize(@$deviser['background'])->resize(350, 344) ?>">
                        <figcaption>
                            <img class="showcase-image" src="<?= Utils::url_scheme() ?><?= Utils::thumborize(@$deviser['img'])->resize(0, 110) ?>">
                            <span class="name"><?= $deviser['name'] ?>r</span>
                            <span class="location">Boston, Massachusetts</span>
                        </figcaption>
                    </figure>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<!-- /SHOWCASE -->

<!-- GRID -->
<section class="grid-wrapper">
    <div class="container">
        <div class="section-title">
            Highlighted Works
        </div>
        <div>
            <div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="/imgs/photo-grid-1.jpg">
                            <figcaption>
                                <p class="instauser">
                                    Black dress
                                </p>
                                <p class="price">€ 3.230</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="/imgs/photo-grid-2.jpg">
                            <figcaption>
                                <p class="instauser">
                                    Amazing bag
                                </p>
                                <p class="price">€ 230</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="/imgs/photo-grid-3.jpg">
                            <figcaption>
                                <p class="instauser">
                                    White &amp; grey dress
                                </p>
                                <p class="price">€ 1.444</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="/imgs/photo-grid-4.jpg">
                            <figcaption>
                                <p class="instauser">
                                    Water scarf
                                </p>
                                <p class="price">€ 3.230</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="/imgs/photo-grid-5.jpg">
                            <figcaption>
                                <p class="instauser">
                                    Dashing jewlery
                                </p>
                                <p class="price">€ 230</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="/imgs/photo-grid-6.jpg">
                            <figcaption>
                                <p class="instauser">
                                    Dashing jewlery
                                </p>
                                <p class="price">€ 1.444</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="/imgs/photo-grid-7.jpg">
                            <figcaption>
                                <p class="instauser">
                                    Color trausers
                                </p>
                                <p class="price">€ 230</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="/imgs/photo-grid-8.jpg">
                            <figcaption>
                                <p class="instauser">
                                    Grey jacket
                                </p>
                                <p class="price">€ 3.230</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="/imgs/photo-grid-9.jpg">
                            <figcaption>
                                <p class="instauser">
                                    Foggy forest
                                </p>
                                <p class="price">€ 230</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="/imgs/photo-grid-10.jpg">
                            <figcaption>
                                <p class="instauser">
                                    Red stripes dress
                                </p>
                                <p class="price">€ 230</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="/imgs/photo-grid-11.jpg">
                            <figcaption>
                                <p class="instauser">
                                    Golden rings
                                </p>
                                <p class="price">€ 3.230</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 pad-grid">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="/imgs/photo-grid-12.jpg">
                            <figcaption>
                                <p class="instauser">
                                    Sculpture lorem ipsum
                                </p>
                                <p class="price">€ 230</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-4 pad-grid pad-grid-h">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="/imgs/photo-grid-h-1.jpg">
                            <figcaption>
                                <p class="instauser">
                                    Sculpture conecteur
                                </p>
                                <p class="price">€ 30.230</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-4 pad-grid pad-grid-h">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="/imgs/photo-grid-h-2.jpg">
                            <figcaption>
                                <p class="instauser">
                                    Red bikini
                                </p>
                                <p class="price">€ 12.002</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            <div class="col-md-4 col-sm-4 pad-grid pad-grid-h">
                <a href="#">
                    <div class="grid">
                        <figure class="effect-zoe">
                            <img class="grid-image" src="/imgs/photo-grid-h-3.jpg">
                            <figcaption>
                                <p class="instauser">
                                    Golden rings
                                </p>
                                <p class="price">€ 8.925</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
<!-- /GRID -->
