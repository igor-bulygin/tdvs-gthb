<?php
use app\assets\desktop\pub\CreateInfluencerAsset;

CreateInfluencerAsset::register($this);

$this->title = 'Create an account - Todevise';

?>


<div class="create-deviser-account-wrapper">
    <div class="logo">
        <h1 class="text-center text-primary">WELCOME<br />to</h1>
        <a href="#">
            <img src="/imgs/logo.png" data-pin-nopin="true">
        </a>
    </div>
    <div class="create-deviser-account-container black-form" ng-controller="createInfluencerCtrl as createInfluencerCtrl">
        <form name="createInfluencerCtrl.form" novalidate>
            <div>
                <div class="row">
                    <label for="name">Name</label>
                    <input type="text" id="name"" class="form-control grey-input ng-class:{'error-input': createInfluencerCtrl.has_error(createInfluencerCtrl.form, createInfluencerCtrl.form.name)}" name="name" ng-model="createInfluencerCtrl.influencer.name" required>
                    <form-errors field="createInfluencerCtrl.form.name" condition="createInfluencerCtrl.has_error(createInfluencerCtrl.form, createInfluencerCtrl.form.name)"></form-errors>
                </div>
                <div class="row">
                    <label for="email">Email</label>
                    <input type="email" id="email" class="form-control grey-input ng-class:{'error-input': createInfluencerCtrl.has_error(createInfluencerCtrl.form, createInfluencerCtrl.form.email)}" name="email" ng-model="createInfluencerCtrl.influencer.email" required>
                    <form-errors field="createInfluencerCtrl.form.email" condition="createInfluencerCtrl.has_error(createInfluencerCtrl.form, createInfluencerCtrl.form.email)"></form-errors>
                </div>
                <div class="row">
                    <label for="password">Set your password</label>
                    <input type="password" id="password" class="form-control grey-input password ng-class:{'error-input':createInfluencerCtrl.has_error(createInfluencerCtrl.form, createInfluencerCtrl.form.password)}" name="password" ng-model="createInfluencerCtrl.influencer.password" required>
                    <form-errors field="createInfluencerCtrl.form.password" condition="createInfluencerCtrl.has_error(createInfluencerCtrl.form, createInfluencerCtrl.form.password)"></form-errors>
                </div>
                <div class="row">
                    <label for="password_confirm">Repeat password</label>
                    <input type="password" id="password_confirm" class="form-control grey-input password ng-class:{'error-input': createInfluencerCtrl.has_error(createInfluencerCtrl.form, createInfluencerCtrl.form.password_confirm) && createInfluencerCtrl.form.password_confirm.$error.same}" name="password_confirm" ng-model="createInfluencerCtrl.password_confirm" required>
                    <div ng-show="createInfluencerCtrl.has_error(createInfluencerCtrl.form, createInfluencerCtrl.form.password_confirm) && createInfluencerCtrl.form.password_confirm.$error.same" tdv-comparator value1="{{createInfluencerCtrl.influencer.password}}" value2="{{createInfluencerCtrl.password_confirm}}" result="createInfluencerCtrl.form.password_confirm.$error.same">
                        <form-messages field="createInfluencerCtrl.form.password_confirm"></form-messages>
                    </div>
                </div>
                <div class="row">
                    <div class="checkbox checkbox-circle remember-me">
                        <input id="checkbox7" class="styled" type="checkbox">
                        <label for="checkbox7">
                            I accept the Todevise Terms & Conditions
                        </label>
                    </div>
                </div>
            </div>
            <button class="btn-red send-btn" ng-click="createInfluencerCtrl.submitForm(createInfluencerCtrl.form)">
                <i class="ion-android-navigate"></i>
            </button>
        </form>
    </div>
</div>