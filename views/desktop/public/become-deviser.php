<?php
use app\assets\desktop\pub\PublicCommonAsset;
use app\components\DeviserHeader;
use app\components\DeviserMenu;
use app\models\Person;
use app\models\Product;
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

PublicCommonAsset::register($this);

/** @var Person $deviser */

$this->title = 'Become a Deviser - Todevise';

?>

<div class="become-deviser-wrapper">
    <div class="container">
        <div class="become-deviser-cover">
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6 col-xs-12">
                    <div class="call-to-action-wrapper">
                        <div class="call-to-action">
                            <div class="logo">
                                <img src="imgs/logo_white.png">
                            </div>
                            <div class="tagline">
                                Express
                            </div>
                            <div class="tagline">
                                Yourself
                            </div>
                            <button class="btn btn-white btn-request">Request invitation</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row bca-row">
                <div class="col-sm-4 pad-product">
                    <div class="bca-content">
                        <img class="clock" src="imgs/clock.svg">
                        <div class="title">Set up your store in minutes</div>
                        <p>Opening your store on Todevise is a fast and efortless process.</p>
                    </div>
                </div>
                <div class="col-sm-4 pad-product">
                    <div class="bca-content">
                        <img src="imgs/map.svg">
                        <div class="title">Join a select community of devisers</div>
                        <p>Become part of a group of creators who share the same passion for innovation and quality as you, and let your creativity flourish.</p>
                    </div>
                </div>
                <div class="col-sm-4 pad-product">
                    <div class="bca-content no-border">
                        <img src="imgs/laptop.svg">
                        <div class="title">Sellers tools</div>
                        <p>Get advanced analytics and manage your sales and customer relations, all in one place.</p>
                    </div>
                </div>
            </div>
            <div class="row bca-row second">
                <div class="col-sm-6 pad-product">
                    <div class="bca-content no-border">
                        <img src="imgs/affiliate.svg">
                        <div class="title">Affiliate program</div>
                        <p>Grow your customer base and sales exponentially with our innovative affiliate program. Promote works and earn funds.</p>
                    </div>
                </div>
                <div class="col-sm-6 pad-product">
                    <div class="bca-content no-border">
                        <span class="big-euro">€</span>
                        <div class="title">Price</div>
                        <p>Enjoy all the Todevise features for only 99€/year (billed annually) and 4% for each transaction. Marketing and affiliates costs not included.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
