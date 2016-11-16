<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\assets\publicMyAccountAsset;


/**** Logged in ****/
if (!Yii::$app->user->isGuest) {

	$deviser = Yii::$app->user->identity;

	/* Logged in /index-old */
	if($_SERVER['REQUEST_URI'] == "/index-old") {

		publicMyAccountAsset::register($this);?>

		<div class='my_account_component'>
			<div class="logged_in flex flex-column">
				<div class="profile_wrapper flex flex-justify-between">
					<div class="profile">
						<img src="" alt=""/>
						<span
							class="funiv_bold fc-1c1919 fs0-813"><?= $deviser["personal_info"]["name"] . " " . (isset($deviser["personal_info"]["surnames"]) && $deviser["personal_info"]["surnames"] != null ? implode(" ", $deviser["personal_info"]["surnames"]) : ""); ?></span>
					</div>
					<?= Html::a(Yii::t("app/public", "View profile"), ["deviser/view-profile"], [
						'class' => 'fs-univ fc-6d fs0-857 view_profile_label'
					]) ?>
				</div>
				<br>
				<?php if($deviser['type'][0] == \app\models\Become::ADMIN) {?>
					<?= Html::a(Yii::t("app/admin", "Admin Panel"), ["/admin"], [
						'class' => 'fs-univ fc-6d fs0-857 text-right orders_label'
					]) ?>
				<?php
				}?>
				<?= Html::a(Yii::t("app/public", "My orders"), ["public/orders"], [
					'class' => 'fs-univ fc-6d fs0-857 text-right orders_label'
				]) ?>
				<?= Html::a(Yii::t("app/public", "Settings"), ["public/settings"], [
					'class' => 'fs-univ fc-6d fs0-857 text-right settings_label'
				]) ?>
				<?= Html::a(Yii::t("app/public", "Logout"), ["global/logout"], [
					'class' => 'fs-univ fc-6d fs0-857 text-right logout_label'
				]) ?>

			</div>
		</div><?php


	} else {

		/* Logged in index */?>

		<li class="dropdown log">
			<a href="#" class="dropdown-toggle log" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?= $deviser["personal_info"]["name"] . " " . (isset($deviser["personal_info"]["surnames"]) && $deviser["personal_info"]["surnames"] != null?implode(" ", $deviser["personal_info"]["surnames"]):""); ?></span>
		</a>
		<div class="dropdown-menu login-wrapper black-form"><?php

			if($deviser['type'][0] == \app\models\Become::ADMIN) {?>
				<div class="row">
					<?= Html::a(Yii::t("app/admin", "Admin Panel"), ["/admin"], ['class' => 'fs-univ fc-6d fs0-857 text-right orders_label']) ?>
				</div><?php
			}?>

			<div class="row">
				<?= Html::a(Yii::t("app/public", "View profile"), ["deviser/view-profile"], ['class' => 'fs-univ fc-6d fs0-857 view_profile_label']) ?>
			</div>
			<div class="row">
				<?= Html::a(Yii::t("app/public", "My orders"), ["public/orders"], ['class' => 'fs-univ fc-6d fs0-857 text-right orders_label']) ?>
			</div>
			<div class="row">
				<?= Html::a(Yii::t("app/public", "Settings"), ["public/settings"], ['class' => 'fs-univ fc-6d fs0-857 text-right settings_label']) ?>
			</div>
			<div class="row">
				<?= Html::a(Yii::t("app/public", "Logout"), ["global/logout"], ['class' => 'fs-univ fc-6d fs0-857 text-right logout_label']) ?>
			</div>

		</div>
		</li><?php
	}


} else {

	/*** Not logged in ***/

	/* Not logged in /index-old */
	if ($_SERVER['REQUEST_URI'] == '/index-old') { ?>

		<div class="my_account_component">
			<div class="not_logged_in flex flex-column">
				<span class="fpf_blacki fs1-143 fc-46"><?= Yii::t("app/public", "Not a member?") ?></span>
				<span
					class="fpf_i fs1 fc-9b"><?= Yii::t("app/public", "Register now and start enjoying Todevise!") ?></span>
				<br>
				<div
					class="funiv_bold fc-fff fs1-143 bc-c91c39 btn fs-upper"><?= Yii::t("app/public", "Join Todevise") ?></div>
				<br>

				<span class="fpf_blacki fs1-143 fc-46"><?= Yii::t("app/public", "Already a member?") ?></span>
				<span class="fpf_i fs1 fc-9b"><?= Yii::t("app/public", "Login now!") ?></span>

				<br>
				<?php $form = ActiveForm::begin([
					'id' => 'login-form',
					'fieldConfig' => [
						'template' => "<div class='col-xs-12'>{input}</div>\n<div class='col-xs-12'>{error}</div>"
					],
					'options' => [
						'class' => 'flex flex-column'
					]
				]); ?>

				<?= $form->field($login_model, 'email', [
					'inputOptions' => [
						'class' => 'form-control bc-e9 funiv fs0-929 fc-69',
						'placeholder' => Yii::t("app/public", "Email")
					]
				]) ?>
				<?= $form->field($login_model, 'password', [
					'inputOptions' => [
						'class' => 'form-control bc-e9 funiv fs0-929 fc-69',
						'placeholder' => Yii::t("app/public", "Password")
					]
				])->passwordInput() ?>
				<div class="flex flex-justify-between flex-align-center relative">
					<?= Html::a(Yii::t("app/public", "Forgot password?"), ["global/forgot-password"], [
						'class' => 'fs-univ fc-6d fs0-857 forgot_password_label'
					]) ?>
					<?= $form->field($login_model, 'rememberMe', [
						'template' => "<span class='remember_me_label'>" . Yii::t("app/public", "Remember me") . "</span>{input}",
						'options' => [
							'class' => 'fs-univ fc-6d fs0-857 relative'
						]
					])->checkbox([
						'label' => ''
					]) ?>
				</div>

				<?= Html::submitButton(Yii::t('app/public', 'Login'), [
					'class' => 'funiv_bold fc-fff fs1-143 bc-1c1919 btn fs-upper'
				]) ?>

				<?php ActiveForm::end(); ?>

			</div>
		</div><?php
	} else { ?>

		<!-- Not logged in /index-old -->
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

			<div class="dropdown-menu login-wrapper black-form">
				<form id="login-form" action="/" method="post">
					<div class="row">
						<?php $form = ActiveForm::begin([
							'id' => 'login-form2',
							'fieldConfig' => [
								'template' => "<div class='col-xs-12'>{input}</div>\n<div class='col-xs-12'>{error}</div>"
							],
							'options' => [
								'class' => 'flex flex-column'
							]
						]); ?>


						<label>Email</label>
						<?= $form->field($login_model, 'email', [
							'inputOptions' => [
								'class' => 'form-control',
								'placeholder' => Yii::t("app/public", "Email")
							]
						]) ?>
					</div>
					<div class="row">
						<label>Password</label>

						<?= $form->field($login_model, 'password', [
							'inputOptions' => [
								'class' => 'form-control',
								'placeholder' => Yii::t("app/public", "Password")
							]
						])->passwordInput() ?>
					</div>
					<div class="row">
						<?= Html::submitButton(Yii::t('app/public', 'Login'), [
							'class' => 'btn btn-default btn-black'
						]) ?>
						<?php ActiveForm::end(); ?>

					</div>
				</form>
			</div>
		</li><?php
	}
} ?>

