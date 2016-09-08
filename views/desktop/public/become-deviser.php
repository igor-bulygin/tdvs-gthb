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
                <div class="col-sm-6">
                    <div class="call-to-action-wrapper">
                        <div class="call-to-action">
                            <div class="logo">
                                <img src="img/logo_white.png">
                            </div>
                            <div class="tagline">
                                Express
                            </div>
                            <div class="tagline">
                                Yourself
                            </div>
                            <button class="btn btn-white">Request invitation</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
