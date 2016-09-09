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

$this->title = 'Create a Deviser account - Todevise';

?>

<div class="create-deviser-account-wrapper">
    <div class="logo">
        <img src="/imgs/logo.png" data-pin-nopin="true">
    </div>
    <div class="create-deviser-account-container">
        <form>
            <div>
                <div class="row">
                    <label for="email">Email address</label>
                    <input type="email" id="email" class="form-control grey-input">
                </div>
                <div class="row">
                    <label>Brand name</label>
                    <input type="text" class="form-control grey-input">
                </div>
                <div class="row">
                    <label>Representative name <i class="ion-information-circled info"></i></label>
                    <input type="text"  class="form-control grey-input">
                </div>
                <div class="row">
                    <label>Set your password</label>
                    <input type="password" id="email" class="form-control grey-input password">
                </div>
                <div class="row">
                    <label>Repeat password</label>
                    <input type="password" id="email" class="form-control grey-input password">
                </div>
            </div>
            <button class="btn-red send-btn">
                <i class="ion-android-navigate"></i>
            </button>
        </form>
    </div>
</div>
