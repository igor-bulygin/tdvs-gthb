<?php
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Login';

?>

<?php if ($invalidLogin) { ?>
	<div class="alert alert-danger">Invalid login</div>
<?php } ?>

<?php $form = ActiveForm::begin(); ?>
<div class="create-deviser-account-wrapper pt-0">
	<span class="login-title">Log in to your todevise account</span>
	<div class="create-deviser-account-container black-form">
		<div class="row">
			<label for="email">Email</label>
			<input type="email" id="email" name="Login[email]" class="form-control grey-input" />
		</div>

		<div class="row">
			<label for="password">Password</label>
			<input type="password" id="password" name="Login[password]" class="form-control grey-input" />
		</div>

		<div class="row">
			<div class="checkbox checkbox-circle remember-me">
				<input id="checkbox7" name="Login[rememberMe]" class="styled" type="checkbox">
				<label for="checkbox7">
					Remember me
				</label>
			</div>
		</div>

		<div class="row">
			<button type="submit" class="btn-red send-btn" >
				<i class="ion-android-navigate"></i>
			</button>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>

